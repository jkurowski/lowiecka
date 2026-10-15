<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// CMS
use App\Models\Property;
use Illuminate\Support\Facades\Log;

class EndPropertyPromotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * End promotions whose end date (last day of the promotion) has passed.
     */
    public function handle(): void
    {
        $properties = Property::where('highlighted', 1)
            ->whereNotNull('promotion_end_date')
            ->where('promotion_end_date', '!=', '')
            ->where('promotion_end_date', '<', now()->toDateString())
            ->get();

        foreach ($properties as $property) {
            $property->highlighted = 0;
            $property->save();
            Log::info('Promotion ended for property ID: ' . $property->id);
        }
    }
}
