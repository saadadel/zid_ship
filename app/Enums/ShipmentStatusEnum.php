<?php

namespace App\Enums;

enum ShipmentStatusEnum: string
{
    case PENDING = "pending";
    case CREATED = "created";
    case SHIPPED = "shipped";
    case DELIVERING = "delivering";
    case DELIVERED = "delivered";
    case COMPLETED = "completed";
}