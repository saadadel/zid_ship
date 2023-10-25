<?php

namespace App\Services;

use App\DTOs\ShipmentDTO;
use App\Models\Address;
use App\Models\Courier;
use ProductTypeEnum;

class CourierDirector
{
    private CourierBuilder $courier_builder;


    public function __construct(CourierBuilder $courier_builder)
    {
        $this->courier_builder = $courier_builder;
    }

    public function getCourier(ShipmentDTO $shipment_dto): ?Courier
    {
        // Tell the builder that we need a courier matches a specific shipment requirement
        $builder = $this->courier_builder->forShipment($shipment_dto);

        $builder
            // Filter couriers based on the supported cities
            ->supportCities(
                Address::with('city')->findOrFail($shipment_dto->pickup_address_id)->city->name,
                Address::with('city')->findOrFail($shipment_dto->delivery_address_id)->city->name
            )
            // Filter couriers based on the product type
            ->supportProductType();

        // Filter couriers that can deliver before the due date
        if (isset($shipment_dto->due_date)) {
            $builder->durationFilter();
        }

        // Filter couriers that charges less than or equal the max coast
        if (isset($shipment_dto->max_coast)) {
            $builder->coastFilter();
        }

        if ($shipment_dto->courier_selection_method == 'fast') {
            $builder->sortByDuration();
        } else if ($shipment_dto->courier_selection_method == 'cheap') {
            $builder->sortByCoast();
        }

        return $builder->first();
    }
}