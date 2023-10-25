<?php

namespace App\Repositories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Collection;

class CourierRepository
{
    private Courier $courier_model;

    public function __construct(Courier $courier)
    {
        $this->courier_model = $courier;
    }

    /**
     * Return only the couriers that didn't reached their max specified concurrent shipments
     */
    public function getAvailableCouriers(): Collection
    {
        return $this->courier_model->whereColumn('current_shipments', '<', 'max_shipments')->with('cities')->get();
    }
}