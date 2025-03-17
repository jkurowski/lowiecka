<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (! function_exists('getCloudConvertCredits')) {
    function getCloudConvertCredits()
    {
        $apiKey = env('CLOUDCONVERT_API_KEY'); // Get the API key from the .env file

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey
            ])->get('https://api.cloudconvert.com/v2/users/me');

            // Check if the request was successful
            if ($response->successful()) {
                $userData = $response->json(); // Decode the response into an array
                $credits = $userData['data']['credits']; // Get the remaining credits

                Log::info('CloudConvert Credits: ' . $credits);

                return $credits;
            } else {
                Log::error('CloudConvert API Request Failed: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching CloudConvert credits: ' . $e->getMessage());
            return null;
        }
    }
}