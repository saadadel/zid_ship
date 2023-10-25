<?php

namespace App\Enums;

enum PaymentTypeEnum: string
{
    use EnumHelpers;


    case PAID = "paid";
    case COD = "COD";
}