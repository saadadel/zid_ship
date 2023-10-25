<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shipment_num' => $this->shipment_num,
            'consignor_name' => $this->consignor_name,
            'consignor_email' => $this->consignor_email,
            'consignor_phone' => $this->consignor_phone,
            'consignee_name' => $this->consignee_name,
            'consignee_email' => $this->consignee_email,
            'consignee_phone' => $this->consignee_phone,
            'due_date' => $this->due_date,
            'actual_delivery_date' => $this->actual_delivery_date,
            'coast' => $this->coast,
            'pickup_address' => new AddressResource($this->pickupAddress),
            'delivery_address' => new AddressResource($this->deliveryAddress),
            'num_of_pieces' => $this->num_of_pieces,
            'description' => $this->description,
            'status' => $this->status,

        ];
    }
}