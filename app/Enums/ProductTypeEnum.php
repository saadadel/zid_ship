<?php

namespace App\Enums;


enum ProductTypeEnum: string
{
    use EnumHelpers;


    case PDX = "PDX";
    case EPX = "EPX";
    case PPX = "PPX";
}