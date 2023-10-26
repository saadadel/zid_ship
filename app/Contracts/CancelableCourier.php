<?php

namespace App\Contracts;

use App\Models\Shipment;

interface CancelableCourier
{
    public function cancelShipment(Shipment $shipment): bool;
}