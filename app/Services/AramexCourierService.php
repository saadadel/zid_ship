<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Shipment;
use App\DTOs\ShipmentDTO;
use Illuminate\Support\Str;
use App\Enums\ProductTypeEnum;
use App\Enums\ShipmentStatusEnum;
use App\Contracts\CancelableCourier;
use App\Http\Resources\WaybillLabelResource;

class AramexCourierService extends CourierServiceBase implements CancelableCourier
{
    /**
     * Mock class to simulate Aramex integration
     */
    public function initConnection()
    {
        // TODO: use $this->courier->credentials to instantiate the connection
    }

    public function calculateDeliveryDate(ShipmentDTO $shipment_dto): Carbon
    {
        // TODO: use Aramex API to get delivery date estimation
        return Carbon::now()->addDays(3);
    }

    public function calculateCoast(ShipmentDTO $shipment_dto): float
    {
        // TODO: use Aramex API to get coast date estimation
        return 19.0;
    }

    public function createShipment(ShipmentDTO $shipment_dto): Shipment
    {

        // TODO: use Aramex API to create the shipment and get its data
        $courier_shipment_data = $this->courierShipmentData($shipment_dto);
        $courier_shipment_data['shipment_num'] = Str::random(10);
        $courier_shipment_data['status'] = ShipmentStatusEnum::CREATED;

        $shipment_model = new Shipment(array_merge(
            $shipment_dto->toArray(),
            $courier_shipment_data
        ));
        $shipment_model->save();

        return $shipment_model;
    }

    public function printWaybillLabel(Shipment $shipment): array
    {
        return (new WaybillLabelResource($shipment))->resolve();
    }

    public function shipmentStatus(string $shipment_num): ShipmentStatusEnum
    {
        // TODO: get the current status from Aramex API
        $status = "out for delivery";

        $status = $this->mapStatus($status);

        Shipment::where("shipment_num", $shipment_num)->firstOrFail()->update([
            "status" => $status
        ]);

        return $status;
    }

    public function mapStatus(string $shipment_status): ShipmentStatusEnum
    {
        $aramex_statuses_map = [
            "package shipped" => ShipmentStatusEnum::SHIPPED,
            "out for delivery" => ShipmentStatusEnum::DELIVERING,
            "delivered" => ShipmentStatusEnum::DELIVERED,
            "finished" => ShipmentStatusEnum::COMPLETED,
        ];

        return $aramex_statuses_map[$shipment_status];
    }

    public function mapProductType(string $product_type): ProductTypeEnum
    {
        return ProductTypeEnum::EPX;
    }

    public function cancelShipment(Shipment $shipment): bool
    {
        // TODO: Send cancel shipment request to Aramex
        $shipment->status = ShipmentStatusEnum::CANCELED;
        $shipment->save();

        return true;
    }
}