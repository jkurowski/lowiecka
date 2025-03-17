<?php

namespace App\Listeners;

use App\Events\ClientDealsFieldsCreated;
use App\Helpers\ClientSalesStatuses;
use App\Helpers\RoomStatusMaper;
use App\Models\Property;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetPropertiesReserved
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
    public function handle(ClientDealsFieldsCreated $event): void
    {
        if (!$event->field->status == ClientSalesStatuses::DEVELOPER_AGREEMENT || !$event->field->status == ClientSalesStatuses::PRE_SALE_AGREEMENT) {
            return;
        }
        if ($event->field->property_id) {
            $property = Property::find($event->field->property_id);
            $property->update(['status' => $this->mapClientSalesStatusToRoomStatus($event->field->status), 'client_id' => $event->field->client_id]);
        }
        if ($event->field->storage_id) {
            $storage = Property::find($event->field->storage_id);
            $storage->update(['status' => $this->mapClientSalesStatusToRoomStatus($event->field->status), 'client_id' => $event->field->client_id]);
        }
        if ($event->field->parking_id) {
            $parking = Property::find($event->field->parking_id);
            $parking->update(['status' => $this->mapClientSalesStatusToRoomStatus($event->field->status), 'client_id' => $event->field->client_id]);
        }
    }
    private function mapClientSalesStatusToRoomStatus(int $status): int
    {
        switch ($status) {
            case ClientSalesStatuses::DEVELOPER_AGREEMENT:
                return RoomStatusMaper::DEVELOPERS_AGREEMENT;
            case ClientSalesStatuses::PRE_SALE_AGREEMENT:
                return RoomStatusMaper::PRE_SALE_AGREEMENT;
            default:
                return RoomStatusMaper::FREE;
        }
    }
}
