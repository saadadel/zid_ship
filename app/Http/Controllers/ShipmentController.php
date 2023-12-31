<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\DTOs\ShipmentDTO;
use App\Services\CourierDirector;
use Illuminate\Http\JsonResponse;
use App\Services\CourierServiceBase;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;

class ShipmentController extends BaseController
{

    private $courier_director;

    public function __construct(CourierDirector $courier_director)
    {
        $this->courier_director = $courier_director;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShipmentDTO $shipment_dto): JsonResponse
    {
        if (isset($shipment_dto->courier_id)) {

            // If the client choose to go with a specific Courier
            $courier_model = Courier::findOrFail($shipment_dto->courier_id);
        } else {

            // The app chooses the best available courier
            $courier_model = $this->courier_director->getCourier($shipment_dto);
        }

        if (is_null($courier_model)) {
            return $this->sendError("Failed", "There isn't a courier available right now, please try again later");
        }

        // Instantiate a Courier Service class from the Courier Model
        $courier_service = CourierServiceBase::instantiateCourierService($courier_model);

        // Ask the Courier Service to create the shipment
        $shipment = $courier_service->createShipment($shipment_dto);

        return $this->sendResponse(new ShipmentResource($shipment), "Shipment created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        return $this->sendResponse(new ShipmentResource($shipment), "Shipment retrieved successfully");
    }

    /**
     * Display the shipment status
     */
    public function showStatus(Shipment $shipment)
    {
        // Instantiate a Courier Service class from the Courier Model
        $courier_service = CourierServiceBase::instantiateCourierService($shipment->courier);

        // Asks the Courier Service to get the updated shipment status
        $shipmentStatus = $courier_service->shipmentStatus($shipment->shipment_num);

        return $this->sendResponse(["status" => $shipmentStatus], "Status retrieved successfully");
    }

    /**
     * Display the shipment status
     */
    public function cancelShipment(Shipment $shipment)
    {
        // Instantiate a Courier Service class from the Courier Model
        $courier_service = CourierServiceBase::instantiateCourierService($shipment->courier);

        try {
            // Asks the Courier Service to cancel the shipment
            $cancel_res = $courier_service->cancelShipment($shipment);
        } catch (\Throwable $th) {
            return $this->sendError("Failed", "Cancel shipment is not allowed", 400);
        }

        if ($cancel_res) {
            return $this->sendResponse([], "Shipment canceled successfully");
        } else {
            return $this->sendError("Failed", "Cancel shipment is not allowed", 400);
        }
    }
}