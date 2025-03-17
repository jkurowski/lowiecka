<?php

namespace App\Listeners;

use App\Events\ClientDealsFieldsDeleted;
use App\Helpers\RoomStatusMaper;
use App\Models\ClientFields;
use App\Models\Property;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetPropertiesFree
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClientDealsFieldsDeleted $event): void
    {
        $event->fields->each(function (ClientFields $field) {
            if($field->property_id) {
                $property = Property::find($field->property_id);
                $property->update(['status' => RoomStatusMaper::FREE, 'client_id' => null]);
            }
            if($field->storage_id) {
                $storage = Property::find($field->storage_id);
                $storage->update(['status' => RoomStatusMaper::FREE, 'client_id' => null]);
            }
            if($field->parking_id) {
                $parking = Property::find($field->parking_id);
                $parking->update(['status' => RoomStatusMaper::FREE, 'client_id' => null]);
            }
        });
    }
}
