<?php

namespace App\Observers;

use App\Models\PropertyPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

// CMS
use App\Repositories\InvestmentRepository;
use App\Models\PropertiesHistory;
use App\Models\Property;

class PropertyObserver
{
    private $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository)
    {
        $this->investmentRepository = $investmentRepository;
    }

    /**
     * Handle the Property "deleted" event.
     *
     * @param Property $property
     * @return void
     */
    public function deleted(Property $property)
    {
        if (File::isFile(public_path('investment/property/' . $property->file))) {
            File::delete(public_path('investment/property/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/thumbs/' . $property->file))) {
            File::delete(public_path('investment/property/thumbs/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/list/' . $property->file))) {
            File::delete(public_path('investment/property/list/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/thumbs/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/thumbs/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/list/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/list/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/pdf/' . $property->file_pdf))) {
            File::delete(public_path('investment/property/pdf/' . $property->file_pdf));
        }
    }
    /**
     * Handle the Property "updated" event.
     *
     * @param Property $property
     * @return void
     */
    public function updated(Property $property)
    {
        $originalPrice = $property->getOriginal('price_brutto');

        // Handle price change
        if ($property->wasChanged(['price_brutto', 'promotion_price', 'highlighted'])) {

            $newPrice = $property->price_brutto;
            $originalPrice = $property->getOriginal('price_brutto');
            $originalPromotionPrice = $property->getOriginal('promotion_price');
            $originalHighlighted = $property->getOriginal('highlighted');

            $promotionPrice = $property->promotion_price;
            $highlighted = $property->highlighted;

            // ✅ Case 1: Promotion added or changed
            if (
                $highlighted &&
                $promotionPrice !== null &&
                $promotionPrice != $newPrice
            ) {
                $this->logPriceChange($property, $originalPrice, $promotionPrice, true);

                // ✅ Case 2: Promotion removed (highlighted was true, now false)
            } elseif (
                !$highlighted &&
                $originalHighlighted &&
                $originalPromotionPrice !== null &&
                $originalPromotionPrice != $newPrice
            ) {
                $this->logPriceChange($property, $originalPromotionPrice, $newPrice, false);

                // ✅ Case 3: Regular price change (no promotion involved)
            } elseif ($originalPrice !== $newPrice) {

                $this->logPriceChange($property, $originalPrice, $newPrice, false);

            }
        }
    }
    protected function logPriceChange(Property $property, $old, $new, bool $promotion): void
    {
        PropertyPrice::create([
            'property_id' => $property->id,
            'price_brutto' => is_numeric($old) ? $old : 0,
            'new_price_brutto' => $new,
            'area' => $property->area,
            'promotion' => $promotion,
            'changed_at' => now(),
            'changed_by' => Auth::id(),
        ]);
    }
}
