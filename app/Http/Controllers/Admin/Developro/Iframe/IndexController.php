<?php

namespace App\Http\Controllers\Admin\Developro\Iframe;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Services\IframeSettingsService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $iframeSettingsService;

    public function __construct(IframeSettingsService $iframeSettingsService)
    {
        $this->iframeSettingsService = $iframeSettingsService;
    }

    public function index(Investment $investment)
    {
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);
        return view('admin.developro.iframe.index', compact('investment', 'iframeSettings'));
    }

    public function store(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
            'font_family' => 'required|string',
            'font_size' => 'required|integer|min:8|max:24',
            'custom_css' => 'nullable|string',
            'preview_width' => 'required|integer|min:10|max:100',
            'preview_height' => 'required|integer|min:200|max:1000',
            'link_color' => 'required|string',
            'link_hover_color' => 'required|string',
            'box_offer_bg_color' => 'required|string',
            'box_offer_margin' => 'required|string',
            'box_offer_padding' => 'required|string',
            'box_offer_title_font_size' => 'required|integer|min:8|max:24',
        ]);

        $this->iframeSettingsService->saveSettings($investment, $validated);

        return redirect()->route('admin.developro.investment.iframe.index', $investment)
            ->with('success', 'Zapisano zmiany');
    }
}
