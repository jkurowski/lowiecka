<?php

namespace App\Http\Controllers\Admin\Crm\Offer;

use App\DTOs\PropertySearchCriteria;
use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Mail\OfferSend;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

// CMS
use App\Repositories\Client\ClientRepository;
use App\Http\Requests\OfferFormRequest;
use App\Http\Requests\PropertySearchRequest;
use App\Models\Investment;
use App\Models\Offer;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Services\Property\PropertySearchService;
use App\Services\SMS\SMSService;
use Illuminate\Support\Facades\View;

class IndexController extends Controller
{
    private ClientRepository $clientRepository;
    private SMSService $smsService;

    public function __construct(ClientRepository $clientRepository, SMSService $smsService)
    {
        $this->clientRepository = $clientRepository;
        $this->smsService = $smsService;
    }
    public function index()
    {
        return view("admin.crm.offer.index");
    }

    public function drafts()
    {
        return view("admin.crm.offer.drafts");
    }

    public function create($id = null)
    {

        $investment = Investment::find(1);

        if (!$id) {
            $offer = new Offer(); // Create a new instance
            $offer->status = 2; // Set the status to draft
            $offer->user_id = auth()->id(); // Set the user ID if needed
            $offer->client_id = 0; // Set the user ID if needed
            $offer->title = 'Temat wiadomości'; // Set the user ID if needed
            $offer->message = 'Dzień dobry,'; // Set the user ID if needed
            $offer->save();

            return redirect(route('admin.crm.offer.create', ['id' => $offer->id]));
        }

        $offer = Offer::with('client')->findOrFail($id);

        if ($offer->attachments) {
            $attachments = json_decode($offer->attachments, true);
        } else {
            $attachments = array();
        }

        if ($offer->properties) {
            $propertyIds = json_decode($offer->properties);
            $selectedOffer = Property::whereIn('id', $propertyIds)->get();
        } else {
            $selectedOffer = array();
        }

        $templates = $this->getEmailTemplates(TemplateTypes::OFFER);

        $templates = [0 => 'Wybierz szablon'] + $templates;


        return view('admin.crm.offer.form', [
            'cardTitle' => offerStatus($offer->status),
            'backButton' => route('admin.crm.offer.index'),
            'investment' => $investment,
            'attachments' => $attachments,
            'selectedOffer' => $selectedOffer,
            'offer_templates' => $templates,
            'selected_template' => $offer->template_id,
            'selectedInvestment' => $this->getSelectedInvestment($offer),
            'selectedBuilding' => $this->getSelectedBuilding($offer)
        ])->with('entry', $offer);
    }

    public function update(OfferFormRequest $request, Offer $offer)
    {
        // dd($request->all());
        $validatedData = $request->validated();
        $isNewClient = $request->has('is_new_client');

        // Only process client data if it's not a new external client
        if (!$isNewClient) {
            $client = Client::where('mail', $validatedData['client_email'])->first();
            if (!$client) {
                $attributes = [
                    'email' => $validatedData['client_email'],
                    'phone' => $validatedData['client_phone'] ?? null,
                    'name' => $validatedData['client_name'],
                ];

                $client = $this->clientRepository->createClient($attributes, null, 2);
            }

            $offer->client_id = $client->id;
        } else {
            $offer->client_id = 0;
        }

        // Update offer details
        $offer->title = $validatedData['title'];
        $offer->is_new_client = $isNewClient;
        $offer->message = $validatedData['message'];
        $offer->date_end = $validatedData['date_end'];
        $offer->template_id = $validatedData['offer_template'];
        $offer->sended_at = Carbon::now();
        if (!$isNewClient) {
            $offer->status = 1;
        } else {
            $offer->status = 4;
        }
        $offer->save();

        // Send email and SMS only for registered clients
        if (!$isNewClient) {
            Mail::to($validatedData['client_email'])->send(new OfferSend($request, $client, $offer));

            if ($client->phone) {
                $client_phone = preg_replace('/\D/', '', $client->phone);
                $this->smsService->sendNewOfferInfo($client_phone);
            }
        }

        return redirect(route('admin.crm.offer.index'))->with('success', 'Oferta została wysłana');
    }


    public function datatable(Request $request)
    {
        $query = Offer::orderByDesc('created_at');

        if ($request->filled('minDate')) {
            $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDate);
        }

        if ($request->filled('maxDate')) {
            $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDate);
        }

        if($request->filled('status')) {
            $query->whereIn('status', $request->input('status'));
        }

        $list = $query->with(['user', 'client'])->get();

        return Datatables::of($list)
            ->editColumn('contact', function ($row) {
                if ($row->client) {
                    return $row->client->name . ' ' . $row->client->surname;
                }
            })
            ->editColumn('user', function ($row) {
                if ($row->user) {
                    return $row->user->name . ' ' . $row->user->surname;
                }
            })
            ->editColumn('sended_at', function ($row) {
                if (!$row->sended_at) {
                    return '';
                }
                $date = Carbon::parse($row->sended_at)->format('Y-m-d');
                $dateTime = Carbon::parse($row->sended_at)->format('H:i:s');
                return '<span>' . $date . '</span><div class="form-text mt-0">' . $dateTime . '</div>';
            })
            ->editColumn('created_at', function ($row) {
                if (!$row->created_at) {
                    return '';
                }
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $dateTime = Carbon::parse($row->created_at)->format('H:i:s');
                return '<span>' . $date . '</span><div class="form-text mt-0">' . $dateTime . '</div>';
            })
            ->editColumn('readed_at', function ($row) {
                if (!$row->readed_at) {
                    return '';
                }
                $date = Carbon::parse($row->readed_at)->format('Y-m-d');
                $dateTime = Carbon::parse($row->readed_at)->format('H:i:s');
                return '<span>' . $date . '</span><div class="form-text mt-0">' . $dateTime . '</div>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status) {
                    return '<span class="badge offer-status-' . $row->status . '">' . offerStatus($row->status) . '</span>';
                }
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.offer.tableActions', ['row' => $row]);
            })
            ->rawColumns([
                'sended_at',
                'readed_at',
                'created_at',
                'actions',
                'status'
            ])
            ->make();
    }

    public function property(Offer $offer, int $id)
    {
        $existingOfferData = json_decode($offer->properties, true);
        if (!is_array($existingOfferData)) {
            $existingOfferData = [];
        }
        $existingOfferData[] = $id;

        $offer->properties = json_encode($existingOfferData);
        $offer->save();

        return response()->json(['message' => 'Property added to Offer successfully']);
    }

    public function destroy(int $id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return response()->json(['error' => 'Offer not found'], 404);
        }

        $offer->delete();

        return redirect(route('admin.crm.offer.index'))->with('success', 'Oferta została usunięta');
    }

    // public function offerAjaxSearch(Offer $offer, Request $request)

    // {
    //     if ($request->ajax()) {
    //         $selectedInvestment = $request->input('investmentSelect');
    //         $selectedRooms = $request->input('roomsSelect');
    //         $selectedAreaRange = $request->input('areaSelect');
    //         $selectedType = $request->input('typeSelect');

    //         $query = Property::query();

    //         $propertyIds = json_decode($offer->properties);
    //         if (is_array($propertyIds)) {
    //             $query->whereNotIn('id', $propertyIds);
    //         }

    //         if ($selectedInvestment) {
    //             $query->where('investment_id', $selectedInvestment);
    //         }

    //         if ($selectedRooms) {
    //             $query->where('rooms', $selectedRooms);
    //         }

    //         if ($selectedType) {
    //             $query->where('type', $selectedType);
    //         }

    //         if ($selectedAreaRange) {

    //             $areaBounds = explode('-', $selectedAreaRange);
    //             $selectedAreaMin = $areaBounds[0];
    //             $selectedAreaMax = $areaBounds[1];

    //             if ($selectedAreaMin && $selectedAreaMax) {
    //                 $query->whereBetween(DB::raw('CAST(area AS DECIMAL)'), [$selectedAreaMin, $selectedAreaMax]);
    //             }
    //         }
    //         $properties = $query->get();

    //         $view = View::make('admin.developro.investment_shared.properties_list', ['properties' => $properties])->render();
    //         return response()->json(['html' => $view]);
    //     }
    // }

    
    public function offerAjaxSearch(
        Offer $offer,
        PropertySearchRequest $request,
        PropertySearchService $searchService
    ) {
        if (!$request->ajax()) {
            return;
        }

        $searchCriteria = new PropertySearchCriteria(
            $request->input('investmentSelect'),
            $request->input('roomsSelect'),
            1,
            $request->input('buildingSelect'),
            $request->input('areaSelect'),
            $request->input('typeSelect'),
            json_decode($offer->properties)
        );

        $properties = $searchService->search($searchCriteria);
        
        return response()->json([
            'html' => view('admin.developro.investment_shared.properties_list', [
                'properties' => $properties,
                'selectedInvestment' => $this->getSelectedInvestment($offer)
            ])->render()
        ]);
    }


    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string'

        ]);

        $name = $request->input('name');

        // $offers = Offer::where('title', 'like', '%' . $name . '%')->limit(10)->get();
        // restrict user to see only his offers
        $offers = Offer::where('title', 'like', '%' . $name . '%')->where('user_id', auth()->id())->limit(10)->get();

        return response()->json([
            'data' => $offers,
        ]);
    }

    private function getSelectedInvestment(Offer $offer) {
        if(!$offer->properties) {
            return null;
        }
        $propertyIds = json_decode($offer->properties);
        $property = Property::find($propertyIds[0]);
        return $property->investment_id;
    }

    private function getSelectedBuilding(Offer $offer) {
        if(!$offer->properties) {
            return null;
        }
        $propertyIds = json_decode($offer->properties);
        $property = Property::find($propertyIds[0]);
        return $property->building_id;
    }

    private function getEmailTemplates(string $templateType)
    {
        return EmailTemplate::where('user_id', auth()->id())
            ->get()
            ->filter(function ($template) use ($templateType) {
                if ($template->meta) {
                    $meta = $template->meta;
                    if (isset($meta['template_type']) && $meta['template_type'] === $templateType) {
                        return $template;
                    }
                }
            })
            ->pluck('name', 'id')
            ->toArray();
    }
}
