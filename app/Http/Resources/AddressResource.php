<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'country_code' => $this->country_code,
            'post_code' => $this->post_code,
            'state_code' => $this->state_code,
            'city_id' => $this->city_id,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'line3' => $this->line3,
        ];
    }
}