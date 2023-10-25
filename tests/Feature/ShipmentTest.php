<?php

namespace Tests\Feature;

use App\Enums\ShipmentStatusEnum;
use App\Models\Address;
use App\Models\City;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShipmentTest extends TestCase
{
    use RefreshDatabase;

    private $base_request_body;


    protected function setUp(): void
    {
        parent::setUp();

        $this->base_request_body = [
            "courier_selection_method" => "fast",
            "consignee_name" => "Donatello",
            "consignee_email" => "donatello@me.com",
            "consignee_phone" => "+9640102049322",
            "due_date" => "2023-10-30 12:00:00",
            "pickup_address_id" => 1,
            "delivery_address_id" => 2,
            "length" => 25.00,
            "width" => 25.25,
            "height" => 25.50,
            "weight" => 25.00,
            "num_of_pieces" => 4,
            "products_type" => "EPX",
            "payment_type" => "COD",
            "origin_country_code" => "SA"
        ];
    }

    /**
     * A basic feature test example.
     */
    public function test_successful_shipment_creation(): void
    {
        $response = $this->postJson("api/shipment/create", $this->base_request_body);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath("success", true)
            ->assertJsonPath("message", "Shipment created successfully")
            ->assertJsonPath("data.status", ShipmentStatusEnum::CREATED->value);
    }

    /**
     * Test shipment creation in not supported city
     */
    public function test_mismatch_city_shipment_creation(): void
    {

        $city = City::factory()->create();
        $address = Address::factory()->create(['city_id' => $city->id]);
        $request_body = $this->base_request_body;
        $request_body['pickup_address_id'] = $address->id;
        $response = $this->postJson("api/shipment/create", $request_body);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath("success", false)
            ->assertJsonPath("message", "Failed");
    }
}