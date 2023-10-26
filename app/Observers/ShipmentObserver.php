<?php

namespace App\Observers;

use App\Enums\ShipmentStatusEnum;
use App\Models\Shipment;

class ShipmentObserver
{
    /**
     * Increase the current shipments for the parent courier
     */
    public function created(Shipment $shipment): void
    {
        $shipment->courier->current_shipments += 1;
        $shipment->courier->save();
    }

    /**
     * Decrease the current shipments for the parent courier
     * Only if the status updated to completed
     */
    public function updated(Shipment $shipment): void
    {
        if (
            array_key_exists("status", $shipment->getChanges()) &&
            in_array($shipment->status, [ShipmentStatusEnum::COMPLETED, ShipmentStatusEnum::CANCELED])
        ) {
            $shipment->courier->current_shipments -= 1;
            $shipment->courier->save();
        }
    }
}