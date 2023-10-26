<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Courier;
use App\Models\Shipment;
use App\DTOs\ShipmentDTO;
use Illuminate\Support\Str;
use App\Enums\ProductTypeEnum;
use App\Enums\ShipmentStatusEnum;
use Illuminate\Support\Facades\Log;

abstract class CourierServiceBase
{
    private Courier $courier;

    public function __construct(Courier $courier)
    {
        $this->courier = $courier;
    }

    public static function instantiateCourierService(Courier $courier_model): CourierServiceBase
    {
        try {

            return new ("App\Services\\" . ucfirst(Str::camel($courier_model->name)) . "CourierService")($courier_model);
        } catch (\Throwable $th) {

            Log::error("Missing courier service", ["error" => $th]);
            throw $th;
        }
    }

    protected function courierShipmentData(ShipmentDTO $shipment_dto): array
    {
        $data = [];
        if (!isset($shipment_dto->courier_id)) {
            $data['courier_id'] = $this->courier->id;
            $data['consignor_name'] = $this->courier->name;
            $data['consignor_email'] = $this->courier->email;
            $data['consignor_phone'] = $this->courier->phone;
        }

        if (!isset($this->courier->shipment_delivery_date)) {
            $this->courier->shipment_delivery_date = $this->calculateDeliveryDate($shipment_dto);
        }

        if (!isset($this->courier->shipment_coast)) {
            $this->courier->shipment_coast = $this->calculateCoast($shipment_dto);
        }

        $data['actual_delivery_date'] = $this->courier->shipment_delivery_date;
        $data['coast'] = $this->courier->shipment_coast;

        return $data;
    }

    abstract public function initConnection();

    abstract public function calculateDeliveryDate(ShipmentDTO $shipment_dto): Carbon;

    abstract public function calculateCoast(ShipmentDTO $shipment_dto): float;

    abstract public function createShipment(ShipmentDTO $shipment_dto): Shipment;

    abstract public function printWaybillLabel(Shipment $shipment): array;

    abstract public function shipmentStatus(string $shipment_num): ShipmentStatusEnum;

    abstract public function mapStatus(string $shipment_status): ShipmentStatusEnum;

    abstract public function mapProductType(string $product_type): ProductTypeEnum;
}