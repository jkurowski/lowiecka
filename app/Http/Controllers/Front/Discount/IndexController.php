<?php

namespace App\Http\Controllers\Front\Discount;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('front.discount.index');
    }
}
