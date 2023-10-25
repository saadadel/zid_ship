<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaybillLabelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'shipment_num' => $this->shipment_num,
            'consignor_name' => $this->consignor_name,
            'consignor_email' => $this->consignor_email,
            'consignor_phone' => $this->consignor_phone,
            'consignee_email' => $this->consignee_email,
            'consignee_phone' => $this->consignee_phone,
            'pickup_address' => new AddressResource($this->pickupAddress),
            'delivery_address' => new AddressResource($this->deliveryAddress),
            'num_of_pieces' => $this->num_of_pieces,
            'description' => $this->description
        ];
    }
}