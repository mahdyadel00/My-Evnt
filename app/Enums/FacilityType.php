<?php

namespace App\Enums;

enum FacilityType: string
{
    case Parking = 'parking';
    case Food = 'food';
    case Wifi = 'wifi';
    case Bathroom = 'bathroom';
    case Security = 'security';

    /**
     * Get all possible values of the enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
