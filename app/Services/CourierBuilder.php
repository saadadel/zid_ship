<?php

namespace App\Services;

use App\DTOs\ShipmentDTO;
use App\Models\Courier;
use Illuminate\Support\Collection;
use App\Repositories\CourierRepository;

class CourierBuilder
{
    private CourierRepository $courier_repository;
    private Collection $available_couriers;
    private ShipmentDTO $shipment_dto;

    public function __construct(CourierRepository $courier_repository)
    {
        $this->courier_repository = $courier_repository;

        $this->available_couriers = $this->courier_repository->getAvailableCouriers();
    }

    public function forShipment(ShipmentDTO &$shipment_dto): CourierBuilder
    {
        $this->shipment_dto = $shipment_dto;

        return $this;
    }

    public function supportCities(string $source_city, string $dest_city): CourierBuilder
    {
        $this->available_couriers = $this->available_couriers
            ->filter(function (Courier $courier) use ($source_city, $dest_city) {
                return $courier->cities->whereIn('name', [$source_city, $dest_city]);
            });

        return $this;
    }

    public function supportProductType(): CourierBuilder
    {
        $product_type = $this->shipment_dto->products_type;
        $this->available_couriers = $this->available_couriers
            ->filter(function (Courier $courier) use ($product_type) {
                return in_array($product_type, $courier->supported_product_types);
            });

        return $this;
    }

    public function durationFilter(): CourierBuilder
    {
        if (!$this->shipment_dto) {
            return $this;
        }

        $filtered_couriers = collect();

        foreach ($this->available_couriers as $courier) {
            $courier_service = CourierServiceBase::instantiateCourierService($courier);

            $courier->shipment_delivery_date = $courier_service->calculateDeliveryDate($this->shipment_dto);

            if ($this->shipment_dto->due_date->gt($courier->shipment_delivery_date)) {
                $filtered_couriers->add($courier);
            }
        }

        $this->available_couriers = $filtered_couriers;

        return $this;
    }

    public function coastFilter(): CourierBuilder
    {
        if (!$this->shipment_dto) {
            return $this;
        }

        $filtered_couriers = collect();

        foreach ($this->available_couriers as $courier) {
            $courier_service = CourierServiceBase::instantiateCourierService($courier);

            $courier->shipment_coast = $courier_service->calculateCoast($this->shipment_dto);

            if ($courier->shipment_coast <= $this->shipment_dto->max_coast) {
                $filtered_couriers->add($courier);
            }
        }

        $this->available_couriers = $filtered_couriers;

        return $this;
    }

    public function sortByDuration(string $sort_type = 'asc'): CourierBuilder
    {
        if (!$this->shipment_dto) {
            return $this;
        }

        foreach ($this->available_couriers as $courier) {
            if ($courier->shipment_delivery_date) {
                continue;
            }

            $courier_service = CourierServiceBase::instantiateCourierService($courier);
            $courier->shipment_delivery_date = $courier_service->calculateDeliveryDate($this->shipment_dto);
        }

        if ($sort_type == 'asc') {
            $this->available_couriers = $this->available_couriers->sortBy("shipment_delivery_date");
        } else if ($sort_type == 'dsc') {
            $this->available_couriers = $this->available_couriers->sortByDesc("shipment_delivery_date");
        }

        return $this;
    }

    public function sortByCoast(string $sort_type = 'asc'): CourierBuilder
    {
        if (!$this->shipment_dto) {
            return $this;
        }

        foreach ($this->available_couriers as $courier) {
            if ($courier->shipment_coast) {
                continue;
            }

            $courier_service = CourierServiceBase::instantiateCourierService($courier);
            $courier->shipment_coast = $courier_service->calculateDeliveryDate($this->shipment_dto);
        }

        if ($sort_type == 'asc') {
            $this->available_couriers = $this->available_couriers->sortBy("shipment_coast");
        } else if ($sort_type == 'dsc') {
            $this->available_couriers = $this->available_couriers->sortByDesc("shipment_coast");
        }

        return $this;
    }

    public function all(): Collection
    {
        return $this->available_couriers;
    }

    public function first(): Courier
    {
        return $this->available_couriers->first();
    }

    public function last(): Courier
    {
        return $this->available_couriers->last();
    }
}
