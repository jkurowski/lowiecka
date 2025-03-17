<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Events\ClientDealsFieldsCreated;
use App\Events\ClientDealsFieldsDeleted;
use App\Helpers\PropertyAreaTypes;
use App\Helpers\RoomStatusMaper;
use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ClientFormRequest;
use App\Http\Requests\ClientModalFormRequest;

use App\Models\User;
use App\Repositories\Client\ClientRepository;
use App\Models\Client;

use App\Models\ClientFields;
use App\Models\Investment;
use App\Models\RodoRules;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {
        return view('admin.crm.client.index');
    }

    public function show(Client $client)
    {
        $users = User::all()->mapWithKeys(function ($user) {
            return [$user->id => $user->name . ' ' . $user->surname];
        })->prepend('Brak', 0);

        return view('admin.crm.client.show.index', [
            'client' => $client,
            'investment_options' => $this->getInvestmentOptions(),
            'users' => $users
        ]);
    }
    public function getInvestmentProperties(Request $request)
    {
        $validated = $request->validate([
            'investment_id' => 'required|exists:investments,id',
        ]);

        $investment = Investment::findOrFail($validated['investment_id']);
        $properties = $investment->properties()->get();

        if ($properties->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brak dostępnych nieruchomości'
            ], 404);
        }

        $result = $this->mapPropertiesByType($properties);

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    private function mapPropertiesByType($properties): array
    {
        $propertyTypes = [
            'apartments' => PropertyAreaTypes::ROOM_APARTMENT,
            'storages' => PropertyAreaTypes::STORAGE,
            'parkings' => PropertyAreaTypes::PARKING
        ];

        return collect($propertyTypes)->mapWithKeys(function ($type, $key) use ($properties) {
            return [
                $key => $properties
                    ->where('type', $type)
                    ->map(fn($property) => [
                        'id' => $property->id,
                        'name' => $property->name
                    ])
                    ->values()
                    ->all()
            ];
        })->all();
    }

    private function getInvestmentOptions()
    {
        $investments = Investment::all();
        return $investments->map(function ($investment) {
            return [
                'value' => $investment->id,
                'text' => $investment->name
            ];
        });
    }

    public function datatable()
    {
        return $this->repository->getDataTable();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.new-client', ['required_rodo_rules' => $this->getRequiredRodoRules()])->with('entry', Client::make())->render();
        }
    }

    public function createNewCompany() {
        if (request()->ajax()) {
            return view('admin.crm.modal.new-company', ['required_rodo_rules' => $this->getRequiredRodoRules()])->with('entry', Client::make())->render();
        }
    }
    private function getRequiredRodoRules() {
        $required_rules_ids = [1, 2, 3];
        return RodoRules::whereIn('id', $required_rules_ids)->get();
    }

    public function update(ClientFormRequest $request, Client $client)
    {
        if ($request->ajax()) {
            return $this->handleAjaxUpdate();
        }   
        // dd($request->validated());

        $fields = $this->prepareFieldsForUpdate($request->validated());
        $this->updateClient($client, $fields);
        $this->updateClientDealsFields($client, $fields['fields'] ?? []);

        return $this->redirectWithSuccess($client);
    }

    private function handleAjaxUpdate()
    {
        // Implement Ajax update logic here
        return response()->json(['message' => 'Ajax update not implemented yet']);
    }

    private function prepareFieldsForUpdate(array $validated): array
    {
        $isCompany = $validated['is_company'] ?? false;
        $companyFields = ['company_name', 'regon', 'krs', 'address', 'exponent'];

        return array_merge(
            $validated,
            $isCompany ? [] : array_fill_keys($companyFields, null),
            ['is_company' => $isCompany]
        );
    }

    private function updateClient(Client $client, array $fields): void
    {
        $this->repository->update($fields, $client);
    }

    private function updateClientDealsFields(Client $client, array $fields): void
    {
        $fieldsToDelete = $client->dealsFields()->get();
        $client->dealsFields()->delete();
        event(new ClientDealsFieldsDeleted($fieldsToDelete));

        collect($fields)->each(function ($field) use ($client) {
            /** @var ClientFields $createdField */
            $createdField = $client->dealsFields()->create($field);
            event(new ClientDealsFieldsCreated($createdField));
        });
        
    }

    private function redirectWithSuccess(Client $client)
    {
        return redirect(route('admin.crm.clients.show', $client))->with('success', 'Klient zaktualizowany');
    }

    public function store(ClientFormRequest $request)
    {
        if (request()->ajax()) {
            try {
                $client = $this->repository->createClient($request);
                //Mail::to(settings()->get("page_email"))->send(new ChatSend($request, $client));
                return response()->json(['success' => true]);
            } catch (\Throwable $exception) {
            }
        }
    }

    public function storeModal(ClientModalFormRequest $request)
    {
        if (request()->ajax()) {
            try {
                $client = $this->repository->createClient($request);
                //Mail::to(settings()->get("page_email"))->send(new ChatSend($request, $client));
                return response()->json(['success' => true]);
            } catch (\Throwable $exception) {
            }
        }
    }
}
