<?php

trait EnumHelpers
{

    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function keysStr(): string
    {
        return implode(',', array_column(self::cases(), 'name'));
    }
}

enum ProductTypeEnum: string
{
    use EnumHelpers;

    case PDX = "Priority Document Express";
    case EPX = "Economy Parcel Express";
    case PPX = "Priority Parcel Express";
}

print_r(
    ProductTypeEnum::keysStr()
);

$v = "PDX";