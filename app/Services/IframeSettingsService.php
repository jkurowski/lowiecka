<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\IframeSetting;
use Illuminate\Support\Facades\Cache;

class IframeSettingsService
{
  private $defaultSettings = [
    'bg_color' => '#ffffff',
    'text_color' => '#000000',
    'font_family' => 'Arial',
    'font_size' => 16,
    'custom_css' => '',
    'preview_width' => 100,
    'preview_height' => 500,
    'link_color' => '#000000',
    'link_hover_color' => '#000000',
    'box_offer_bg_color' => '#ffffff',
    'box_offer_margin' => '10px',
    'box_offer_padding' => '10px',
    'box_offer_title_font_size' => 16,
  ];

  public function getSettings(Investment $investment)
  {

    $settings = IframeSetting::firstOrCreate(
      ['investment_id' => $investment->id],
      $this->defaultSettings
    );
    return $settings->toArray();
  }

  public function saveSettings(Investment $investment, array $settings)
  {
    $settingsToSave = array_merge($this->defaultSettings, $settings);

    $iframeSettings = IframeSetting::updateOrCreate(
      ['investment_id' => $investment->id],
      $settingsToSave
    );
    return $iframeSettings;
  }
}
