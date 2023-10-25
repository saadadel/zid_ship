<?php

namespace App\Enums;


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