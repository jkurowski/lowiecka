<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;

class InvestmentPropertyController extends Controller
{
    private $pageId;

    public function __construct()
    {
        $this->pageId = 2;
    }

    public function index(Floor $floor, $floorSlug, Property $property, $propertySlug)
    {
        $property->timestamps = false;
        $property->increment('views');
        $page = Page::where('id', $this->pageId)->first();
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();

        $next = $property->findNext($property->investment_id, $property->number_order, null, $property->floor_id);
        $prev = $property->findPrev($property->investment_id, $property->number_order, null, $property->floor_id);

        return view('front.developro.investment_property.index', [
            'floor' => $floor,
            'property' => $property,
            'rules' => $rules,
            'next' => $next,
            'prev' => $prev
        ]);
    }

    public function buildingProperty($slug, $building_slug, $floor_slug, Property $property)
    {
        $property->timestamps = false;
        $property->increment('views');
        $page = Page::where('id', $this->pageId)->first();

        $prev = $property->findPrev($property->investment_id, $property->number_order, $property->building_id);
        $next = $property->findNext($property->investment_id, $property->number_order, $property->building_id);

        return view('front.developro.investment_property.index', [
            'property' => $property,
            'prev' => $prev,
            'next' => $next
        ]);
    }
}
