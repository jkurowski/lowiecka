<?php

namespace App\Http\Controllers\Admin\Crm\EmailTracking;

use App\Http\Controllers\Controller;
use App\Models\EmailSchedule;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    public function track($trackingId)
    {
        $emailSchedule = EmailSchedule::find($trackingId);

        if ($emailSchedule) {
            // Log the email open event
            Log::info("Email opened: {$emailSchedule->id}");

            $emailSchedule->opened_at = now();
            $emailSchedule->save();
        }

        // Return a 1x1 transparent GIF
        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'))
            ->header('Content-Type', 'image/gif');
    }
}
