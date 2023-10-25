<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'demo_url' => $this->demo_url,
            'live_url' => $this->live_url,
            'current_shipments' => $this->current_shipments,
            'max_shipments' => $this->max_shipments
        ];
    }
}