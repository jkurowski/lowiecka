<?php

namespace App\Http\Controllers\Front\Developro\Completed;

use App\Http\Controllers\Controller;
use App\Models\Investment;

class IndexController extends Controller
{
    public function index()
    {
        $investments = Investment::where('status', 2)->get();
        return view('front.developro.investment_completed.index', compact('investments'));
    }

    public function show($slug)
    {
        $investment = Investment::findBySlug($slug);
        return view('front.developro.investment_completed.show', compact('investment'));
    }
}
