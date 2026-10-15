<?php

namespace App\Http\Controllers\Admin\Developro\Property;

use App\Helpers\PropertyAreaTypes;
use App\Http\Controllers\Controller;

//CMS
use App\Http\Requests\PropertyFormRequest;
use App\Models\Client;
use App\Repositories\InvestmentRepository;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;
use Illuminate\Http\Request;

use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyProperty;

class OthersController extends Controller
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

    public function create(Investment $investment, Floor $floor)
    {
        return view('admin.developro.investment_property.other_form', [
            'cardTitle' => 'Dodaj powierzchnię',
            'backButton' => route('admin.developro.investment.properties.index', [$investment, $floor]),
            'floor' => $floor,
            'investment' => $investment
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

        return redirect(route('admin.developro.investment.properties.index', [$investment, $floor]))->with('success', 'Powierzchnia zapisana');
    }

    public function edit(Investment $investment, Floor $floor, Property $other)
    {
        return view('admin.developro.investment_property.other_form', [
            'cardTitle' => 'Edytuj powierzchnię',
            'backButton' => route('admin.developro.investment.properties.index', [$investment, $floor]),
            'floor' => $floor,
            'investment' => $investment,
            'entry' => $other
        ]);
    }

    public function update(PropertyFormRequest $request, Investment $investment, Floor $floor, Property $other)
    {
        $this->repository->update($request->validated(), $other);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $other, true);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $other, true);
        }

        return redirect(route('admin.developro.investment.properties.index', [$investment, $floor]))->with('success', 'Powierzchnia zaktualizowana');
    }

    public function destroy(Investment $investment, Floor $floor, int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

}
