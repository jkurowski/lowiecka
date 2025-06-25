<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Page;
use App\Repositories\FloorRepository;
use Illuminate\Http\Request;

// CMS

class InvestmentFloorController extends Controller
{
    private $repository;
    private $pageId;

    public function __construct(FloorRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 2;
    }

    public function index(Floor $floor, Request $request)
    {
        $investment = Investment::find(1);

        $investment_room = $investment->load(array(
            'floorRooms' => function ($query) use ($floor, $request) {
                $query->where('floor_id', $floor->id);
                if ($request->input('rooms')) {
                    $query->where('rooms', $request->input('rooms'));
                }
                if ($request->input('status')) {
                    $query->where('status', $request->input('status'));
                }
                if ($request->input('area')) {
                    $area_param = explode('-', $request->input('area'));
                    $min = $area_param[0];
                    $max = $area_param[1];
                    $query->whereBetween('area_search', [$min, $max]);
                }
                if ($request->input('sort')) {
                    $order_param = explode(':', $request->input('sort'));
                    $column = $order_param[0];
                    $direction = $order_param[1];
                    $query->orderBy($column, $direction);
                }
            },
            'floor' => function ($query) use ($floor) {
                $query->where('id', $floor->id);
            }
        ));

        $page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment_floor.index', [
            'investment' => $investment_room,
            'properties' => $investment_room->floorRooms,
            'uniqueRooms' => $this->repository->getUniqueRooms($floor->properties()),
            'next_floor' => $floor->findNext($investment->id, $floor->position),
            'prev_floor' => $floor->findPrev($investment->id, $floor->position),
            'page' => $page
        ]);
    }

    public function buildingFloor($slug, $building_slug, Floor $floor, Request $request)
    {
        $request->validate([
            'rooms' => 'nullable|integer',
            'floor' => 'nullable|integer',
            'status' => 'nullable|integer',
            'area'  => 'nullable|string|regex:/^\d+-\d+$/',
        ]);

        // Parse URL parameters
        $s_area = $request->input('area');
        $s_rooms = $request->has('rooms') ? (int)$request->input('rooms') : null;
        $s_floor = $request->has('floor') ? (int)$request->input('floor') : null;
        $s_status = $request->input('status');

        // Find the investment by slug and eager load related data
        $investment = Investment::findBySlug($slug)
            ->load([
                'properties.building',
                'properties.floor',
            ]);

        // Filter properties
        $filteredProperties = $investment->properties->filter(function ($property) use ($s_area, $s_rooms, $floor, $s_status) {
            // Parse area range
            if ($s_area) {
                [$minArea, $maxArea] = explode('-', $s_area);
                if ($property->area < $minArea || $property->area > $maxArea) {
                    return false;
                }
            }

            // Filter by rooms
            if ($s_rooms && $property->rooms != $s_rooms) {
                return false;
            }

            // Filter by floor
            if ($property->floor->number != $floor->number) {
                return false;
            }

            // Filter by building
            if ($property->building->id != $floor->building->id) {
                return false;
            }

            // Filter by status
            if ($s_status && $property->status != $s_status) {
                return false;
            }

            return true;
        })->sortBy('number_order');

        $page = Page::where('id', $this->pageId)->first();

        $next = $floor->findNext($investment->id, $floor->position, $floor->building->id);
        $prev = $floor->findPrev($investment->id, $floor->position, $floor->building->id);

        return view('front.developro.investment_building_floor.index', [
            'filtered_properties' => $filteredProperties->values(),
            'investment' => $investment,
            'building' => $floor->building,
            'floor' => $floor,
            'uniqueRooms' => $this->repository->getUniqueRooms($floor->properties()),
            'next_floor' => $next,
            'prev_floor' => $prev,
            'page' => $page
        ]);
    }
}
