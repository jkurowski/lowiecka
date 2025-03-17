<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IframeSetting extends Model
{
    protected $fillable = [
        'investment_id',
        'bg_color',
        'text_color',
        'font_family',
        'font_size',
        'custom_css',
        'preview_width',
        'preview_height',
        'link_color',
        'link_hover_color',
        'box_offer_bg_color',
        'box_offer_margin',
        'box_offer_padding',
        'box_offer_title_font_size',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}

