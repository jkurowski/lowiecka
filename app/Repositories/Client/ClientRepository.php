<?php

namespace App\Repositories\Client;

use App\Models\Client;
use App\Models\ClientFile;
use App\Models\ClientMessage;
use App\Models\ClientMessageArgument;
use App\Models\ClientRules;
use App\Models\Property;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    protected $model;
    protected $client_rules;
    protected $client_files;

    public function __construct(Client $model, ClientRules $client_rules, ClientFile $client_files)
    {
        parent::__construct($model);
        $this->client_rules = $client_rules;
        $this->client_files = $client_files;
    }


    //?name=&lastname=&phone=&email

    public function getDataTable()
    {

        $query = $this->model->latest();

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->where('name', 'like', '%' . $name . '%');
        }

        if (request()->filled('lastname')) {
            $lastname = request()->input('lastname');
            $query->where('lastname', 'like', '%' . $lastname . '%');
        }

        if (request()->filled('phone')) {
            $phone = request()->input('phone');
            $query->where(function ($q) use ($phone) {
                $q->where('phone', 'like', '%' . $phone . '%')
                    ->orWhere('phone2', 'like', '%' . $phone . '%');
            });
        }

        if (request()->filled('email')) {
            $email = request()->input('email');
            $query->where(function ($q) use ($email) {
                $q->where('mail', 'like', '%' . $email . '%')
                    ->orWhere('mail2', 'like', '%' . $email . '%');
            });
        }

        $query->whereHas('properties');

        $query->with('seller');
        $list = $query->get();

        return Datatables::of($list)
            ->addColumn('name', function ($row) {
                return '<a href="' . route('admin.crm.clients.show', $row) . '">' . $row->name . ' ' . $row->lastname . '</a>';
            })
            ->addColumn('seller', function ($row) {
                if($row->user_id){
                    return $row->seller->name . ' ' . $row->seller->surname;
                }
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.client.actions', ['row' => $row]);
            })
            ->editColumn('created_at', function ($row) {
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if ($date >= $now) {
                    return '<span>' . $date . '</span>';
                } else {
                    return '<span>' . $date . '</span><div class="form-text mt-0">' . $diffForHumans . '</div>';
                }
            })
            ->rawColumns(['name', 'seller', 'actions', 'created_at'])
            ->make();
    }

    public function getUserRodo($client, $attributes = null): object
    {
        return $this->client_rules->where('client_id', $client->id)
            ->when(isset($attributes['status']), function ($query) use ($attributes) {
                $query->where('status', $attributes['status']);
            })
            ->get();
    }

    public function getUserFiles($client, ?bool $status = null): object
    {
        return $this->client_files->where('client_id', $client->id)
            ->when($user_id = auth()->id(), function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('active', $status);
            })
            ->get(['id', 'user_id', 'name', 'description', 'file', 'mime', 'size', 'active', 'created_at', 'updated_at']);
    }

    private function isCompany($attributes) {
        if (isset($attributes['is_company']) && ($attributes['is_company'] == 1 || $attributes['is_company'] == 'on')) {
            return true;
        }
        return false;
    }

    private function storeCompanyFields($attributes, Client $client) {
        $client->is_company = $attributes['is_company'];
        $client->company_name = $attributes['company_name'];
        $client->address = $attributes['address'];
        $client->regon = $attributes['regon'];
        $client->krs = $attributes['krs'];
        $client->nip = $attributes['nip'];
        $client->exponent = $attributes['exponent'];
        $client->save();
    }

    public function createClient($attributes, $property = null, $status = 1, $source = null)
    {
        Log::info('Call createClient');

        $utm_array = []; // Initialize as an empty array

        if (isset($attributes['cookie']) && is_array($attributes['cookie'])) {
            $utm_array = array_filter($attributes->cookie()); // Set only if cookies exist
            unset($utm_array['XSRF-TOKEN'], $utm_array['laravel_session']);
        }

        Log::info('Request email: ' . $attributes['email']);
        Log::info('Request phone: ' . $attributes['phone']);
        Log::info('Request name: ' . $attributes['name']);
        Log::info('Request lastname: ' . $attributes['lastname']);

        if (!empty($attributes['lastname'])) {
            Log::info('Request lastname: ' . $attributes['lastname']);
        } else {
            Log::info('Request: lastname not provided');
        }

        Log::info('Request status: ' . $status);

        try {
            // Additional logging before updateOrCreate
            Log::info('Attempting to updateOrCreate client');

            //            $client = $this->model->updateOrCreate(
            //                ['mail' => $attributes['email']],
            //                [
            //                    'phone' => $attributes['phone'] ?? NULL,
            //                    'name' => $attributes['name'],
            //                    'status' => $status,
            //                    'updated_at' => now()
            //                ]
            //            );

            // Find the record by email or create a new instance
            $client = $this->model->firstOrNew(['mail' => $attributes['email']]);

            // Check if the client already exists
            if ($client->exists) {
                // Client exists, update attributes
                $client->phone = $attributes['phone'] ?? null;
                $client->name = $attributes['name'];
                $client->lastname = $attributes['lastname'] ?? null;
                $client->status = $status;
                $client->updated_at = now();

                // Save and trigger the 'updated' event
                if ($this->isCompany($attributes)) {
                    $this->storeCompanyFields($attributes, $client);
                }

                $client->save();
            } else {
                // Client does not exist, set attributes
                $client->phone = $attributes['phone'] ?? null;
                $client->name = $attributes['name'];
                $client->lastname = $attributes['lastname'] ?? null;
                $client->status = $status;
                $client->created_at = now();
                $client->updated_at = now();
                $client->email_tracking_id = Str::uuid()->toString();


                // Save and trigger the 'created' event
                if ($this->isCompany($attributes)) {
                    $this->storeCompanyFields($attributes, $client);
                }

                $client->save();
            }

            if ($client->wasRecentlyCreated) {
                Log::info('Client was created: ' . $client->id);
            } else {
                Log::info('Client was updated: ' . $client->id);
            }
        } catch (\Exception $e) {
            Log::error('Error during updateOrCreate: ' . $e->getMessage());
            Log::error('Error during updateOrCreate: ' . $e->getTraceAsString());
        }

        if (isset($attributes['message']) && $client->id) {

            //$source = strtok($attributes->headers->get('referer'), '?');

            $msg = ClientMessage::create([
                'client_id' => $client->id,
                'message' => $attributes['message'],
                'ip' => $attributes['ip'] ?: $attributes->ip(),
                'source' => $source ?? $attributes['page'],
                'source_type' => 1,
            ]);

            $arguments = [];
            if ($property) {
                $propertyMappings = [
                    'investment_id' => $property->investment_id,
                    'building_id' => $property->building_id,
                    'floor_id' => $property->floor_id,
                    'property_id' => $property->id,
                    'rooms' => $property->rooms,
                    'area' => $property->area,
                ];

                // Now $utm_array will always be defined, even if empty
                $arguments = array_merge($propertyMappings, $utm_array);
            }

            if ($source && isset($attributes['is_external_source'])) {
                $arguments['is_external'] = $attributes['is_external_source'];
            }

            if (isset($attributes['investment_id']) && isset($attributes['investment_name'])) {
                $arguments = array_merge(
                    $arguments,
                    ['investment_id' => $attributes['investment_id']],
                    ['investment_name' => $attributes['investment_name']]
                );
            }
            if (isset($attributes['property_name'])) {
                $arguments = array_merge($arguments, ['property_name' => $attributes['property_name']]);
            }

            if (!empty($arguments)) {
                $msg->arguments = json_encode($arguments);
            }

            $msg->save();
        } else {
            $msg = ClientMessage::create([
                'client_id' => $client->id,
                'message' => 'Klient dodany w systemie',
                'ip' => '127.0.0.1',
                'source' => 'Formularz w systemie',
                'source_type' => 2,
            ]);

            $msg->save();
        }

        return $client;
    }
}
