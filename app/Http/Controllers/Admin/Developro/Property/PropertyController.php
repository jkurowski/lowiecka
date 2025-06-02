<?php

namespace App\Http\Controllers\Admin\Developro\Property;

use App\Helpers\PropertyAreaTypes;
use App\Http\Controllers\Controller;

//CMS
use App\Http\Requests\PropertyFormRequest;
use App\Jobs\EndPropertyPromotion;
use App\Models\Client;
use App\Repositories\InvestmentRepository;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;
use Illuminate\Http\Request;

use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyProperty;

class PropertyController extends Controller
{
    private PropertyRepository $repository;
    private PropertyService $service;
    private InvestmentRepository $investmentRepository;

    public function __construct(PropertyRepository $repository, PropertyService $service, InvestmentRepository $investmentRepository)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
        $this->investmentRepository = $investmentRepository;
    }

    public function index(Investment $investment, Floor $floor)
    {
        $list = $investment->load(array(
            'floorRooms' => function($query) use ($floor)
            {
                $query->where('floor_id', $floor->id);
            }
        ));

        return view('admin.developro.investment_property.index', [
            'investment' => $investment,
            'floor' => $floor,
            'list' => $list,
            'count_property_status' => $list->floorRooms->countBy('status')
        ]);
    }

    public function create(Investment $investment, Floor $floor)
    {
        $others = Property::where('investment_id', '=', $investment->id)
            ->where('status', '=', 1)
            ->whereNull('client_id')
            ->pluck('name', 'id');

        return view('admin.developro.investment_property.form', [
            'cardTitle' => 'Dodaj powierzchnię',
            'backButton' => route('admin.developro.investment.properties.index', [$investment, $floor]),
            'floor' => $floor,
            'investment' => $investment,
            'others' => $others,
            'related' => collect()
        ])->with('entry', Property::make());
    }

    public function store(PropertyFormRequest $request, Investment $investment, Floor $floor)
    {
        $property = $this->repository->create(array_merge($request->validated(), [
            'investment_id' => $investment->id,
            'floor_id' => $floor->id
        ]));

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property);
        }

        if ($request->hasFile('file2')) {
            $this->service->upload2($request->name, $request->file('file2'), $property);
        }
        return redirect(route('admin.developro.investment.properties.index', [$investment, $floor]))->with('success', 'Powierzchnia zapisana');
    }

    public function edit(Investment $investment, Floor $floor, Property $property)
    {
        // Get all properties for the investment except the current property
        $others = Property::where('investment_id', '=', $investment->id)
            ->where('id', '<>', $property->id)
            ->where('status', '=', 1)
            ->whereNull('client_id')
            ->pluck('name', 'id');

        $related = $property->relatedProperties;

        $isRelated = PropertyProperty::where('related_property_id', $property->id)->exists();

        return view('admin.developro.investment_property.form', [
            'cardTitle' => 'Edytuj powierzchnię',
            'backButton' => route('admin.developro.investment.properties.index', [$investment, $floor]),
            'floor' => $floor,
            'investment' => $investment,
            'entry' => $property,
            'others' => $others,
            'related' => $related,
            'isRelated' => $isRelated
        ]);
    }

    private function updateClientDealsFieldsWhenClientIsUnset(Property $property)
    {

        if($property->client_id) {

            $client = Client::find($property->client_id);

            if($property->type == PropertyAreaTypes::ROOM_APARTMENT) {
                $client->dealsFields()->where('property_id', $property->id)->update(['property_id' => null]);
            }
            if($property->type == PropertyAreaTypes::STORAGE) {
                $client->dealsFields()->where('storage_id', $property->id)->update(['storage_id' => null]);
            }
            if($property->type == PropertyAreaTypes::PARKING) {
                $client->dealsFields()->where('parking_id', $property->id)->update(['parking_id' => null]);
            }
        }
    }

    public function update(PropertyFormRequest $request, Investment $investment, Floor $floor, Property $property)
    {
        // dd($property);
        $old_client_id = $property->client_id;
        $new_client_id = $request->validated()['client_id'];

        if($new_client_id == 0) {
            $this->updateClientDealsFieldsWhenClientIsUnset($property);
        }

        $this->repository->update($request->validated(), $property);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property, true);
        }

        if ($request->hasFile('file2')) {
            $this->service->upload2($request->name, $request->file('file2'), $property, true);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property, true);
        }

        // Dispatch the EndPropertyPromotion job if the promotion end date is set
        if ($request->filled('promotion_end_date') && $request->highlighted == 1) {
//            $promotionEndDate = $request->input('promotion_end_date');
//            $delay = now()->diffInSeconds($promotionEndDate, false);
//
//            if ($delay > 0) {  // Only dispatch if the end date is in the future
//                EndPropertyPromotion::dispatch($property)->delay($delay);
//            }

            $delay = now()->addSeconds(3600);  // Delay for 1 minute for testing
            EndPropertyPromotion::dispatch($property->id)->delay($delay);
        }
        return redirect(route('admin.developro.investment.properties.index', [$investment, $floor]))->with('success', 'Powierzchnia zaktualizowana');
    }

    public function destroy(Investment $investment, Floor $floor, int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function fetchProperties(Investment $investment) {
        $properties = $investment->selectProperties()->get();
        $types = $properties->groupBy('type');
        $result = [];
        foreach ($types as $type => $properties) {
            $result[$type] = $properties;
        }
        return response()->json($result);
    }

    public function fetchAvailableProperties(Investment $investment) {
        $properties = $investment->selectAvailableProperties()->get();

        if ($properties->isEmpty()) {
            return response()->json([]);
        }

        $types = $properties->groupBy('type');
        $result = [];
        foreach ($types as $type => $properties) {
            $result[$type] = $properties;
        }
        return response()->json($result);
    }

    public function storerelated(Request $request, $investmentId, $floorId, $propertyId)
    {
        $request->validate([
            'related_property_id' => 'required|exists:properties,id',
        ]);

        $related_id = $request->input('related_property_id');

        $isRelated = PropertyProperty::where('related_property_id', $related_id)->exists();
        $related_property = Property::findOrFail($related_id);

        if ($isRelated) {
            return getRelatedType($related_property->type);
        }

        $property = Property::findOrFail($propertyId);
        $property->relatedProperties()->attach($related_id);

        // Return a response
        return view('admin.developro.investment_shared.related', ['property' => $related_property]);
    }

    public function removerelated(Request $request, $investmentId, $floorId, $propertyId)
    {
        // Validate the input
        $request->validate([
            'related_id' => 'required|exists:properties,id',
        ]);

        $relatedId = $request->input('related_id');

        $property = Property::findOrFail($propertyId);
        $isRelated = $property->relatedProperties()->where('related_property_id', $relatedId)->exists();

        if ($isRelated) {
            $property->relatedProperties()->detach($relatedId, ['client_id' => null]);

            return response()->json([
                'status' => 'removed'
            ]);
        }
    }
}
