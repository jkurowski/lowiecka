<?php

namespace App\Http\Controllers\Admin\Developro\Floor;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloorFormRequest;
use App\Models\Floor;
use App\Models\Investment;
use App\Repositories\FloorRepository;
use App\Services\FloorService;

class FloorController extends Controller
{
    private $repository;
    private $service;

    public function __construct(FloorRepository $repository, FloorService $service)
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
    }

    public function index(Investment $investment)
    {
        return view('admin.developro.investment_floor.index', [
            'investment' => $investment,
            'list' => $investment->floors
        ]);
    }

    public function create(Investment $investment)
    {
        $investment->load('plan');
        return view('admin.developro.investment_floor.form', [
            'cardTitle' => 'Dodaj pietro',
            'backButton' => route('admin.developro.investment.floors.index', $investment),
            'investment' => $investment,
        ])->with('entry', Floor::make());
    }

    public function store(FloorFormRequest $request, Investment $investment)
    {
        $floor = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $floor, $investment->id);
        }

        return redirect(route('admin.developro.investment.floors.index', $investment))->with('success', 'Nowe piętro dodane');
    }

    public function edit(Investment $investment, Floor $floor)
    {
        return view('admin.developro.investment_floor.form', [
            'cardTitle' => 'Edytuj pietro',
            'backButton' => route('admin.developro.investment.floors.index', $investment),
            'entry' => $floor,
            'investment' => $investment
        ]);
    }

    public function update(FloorFormRequest $request, Investment $investment, int $id)
    {

        $floor = $this->repository->find($id);
        $this->repository->update($request->validated(), $floor);

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $floor, $investment->id, true);
        }

        return redirect()->route('admin.developro.investment.floors.index', $investment)->with('success', 'Pietro zaktualizowane');
    }

    public function copy(Investment $investment, Floor $floor)
    {
        $newFloor = $floor->replicate();
        $newFloor->html = '';
        $newFloor->cords = '';
        $newFloor->file = '';
        $newFloor->file_webp = '';
        $newFloor->number = $floor->number + 1;
        $newFloor->position = $floor->position + 1;
        $newFloor->save();
        return redirect()->route('admin.developro.investment.floors.index', $investment)->with('success', 'Pietro skopiowane');
    }

    public function destroy(Investment $investment, Floor $floor)
    {
        $this->repository->delete($floor->id);
        return response()->json('Deleted');
    }
}
