<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

// CMS
use App\Models\Page;
use App\Repositories\InvestmentRepository;

class InvestmentController extends Controller
{
    private InvestmentRepository $repository;
    private int $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 4;
    }

    public function show(Request $request)
    {
        $request->validate([
            'rooms' => 'nullable|integer',
            'floor' => 'nullable|integer',
            'status' => 'nullable|integer',
            'area'  => 'nullable|string|regex:/^\d+-\d+$/',
        ]);

        // Parse URL parameters
        $area = $request->input('area');
        $rooms = $request->has('rooms') ? (int)$request->input('rooms') : null;
        $floor = $request->has('floor') ? (int)$request->input('floor') : null;
        $status = $request->input('status');

        // Find the investment by slug and eager load related data
        $investment = Investment::find(1)
            ->load([
                'properties.building',
                'properties.floor',
            ]);

        // Filter properties
        $filteredProperties = $investment->properties->filter(function ($property) use ($area, $rooms, $floor, $status) {
            // Parse area range
            if ($area) {
                [$minArea, $maxArea] = explode('-', $area);
                if ($property->area < $minArea || $property->area > $maxArea) {
                    return false;
                }
            }

            // Filter by rooms
            if ($rooms && $property->rooms != $rooms) {
                return false;
            }

            // Filter by floor
            if ($floor && $property->floor->number != $floor) {
                return false;
            }

            // Filter by status
            if ($status && $property->status != $status) {
                return false;
            }

            return true;
        })->sortBy('number_order');

        $page = Page::find($this->pageId);

        return view('front.developro.investment_plan.index', [
            'investment' => $investment,
            'page' => $page,
            'properties' => $filteredProperties->values()
        ]);
    }
}
