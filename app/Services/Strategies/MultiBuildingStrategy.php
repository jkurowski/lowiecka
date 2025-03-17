<?php

namespace App\Services\Strategies;

use App\Interfaces\InvestmentTypeStrategy;
use App\Models\Investment;
use Illuminate\Http\Request;

class MultiBuildingStrategy implements InvestmentTypeStrategy
{
    private Investment $investment;
    private Request $request;
    private $investment_room;

    public function __construct(Request $request, Investment $investment)
    {
        $this->investment = $investment;
        $this->request = $request;
    }
    public function handle()
    {
        $filters = $this->request->only(['rooms', 'status', 'building', 'area', 'sort']);

        $this->investment_room = $this->investment->load([
            'floorRooms' => function ($query) use ($filters) {
                $query->filtered($filters)
                    ->orderBy('highlighted', 'DESC')
                    ->orderBy('number_order', 'ASC');

                if (request()->has('floor') && request()->input('floor') !== null) {
                    // Get IDs of all floors with the same position
                    $floorIds = \App\Models\Floor::where('investment_id', $this->investment->id)
                        ->where('position', request()->input('floor'))
                        ->pluck('id'); // Retrieve only IDs

                    $query->whereIn('floor_id', $floorIds);
                }

                if ($this->investment->show_properties == 3) {
                    $query->where('status', 1);
                }

                // Only flats
                $query->where('properties.type', 1);
            }
        ]);

        return $this->investment_room->floorRooms ?? collect([]);
    }

    public function getInvestmentRoom()
    {
        return $this->investment_room;
    }
}
