<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Page;
use App\Models\Property;

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

        return view('front.developro.investment_property.index', [
            'floor' => $floor,
            'property' => $property
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
