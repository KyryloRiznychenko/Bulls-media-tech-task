<?php

namespace App\Enums\Delivery;

enum DeliveryServiceNameStringEnum: string
{
    case NOVAPOSHTA = 'Nova Poshta';
    case UKRPOSHTA = 'Ukrposhta';
    case JUSTIN = 'Justin';

    /**
     * @return string[]
     */
    public static function getAvailableServicesName(): array
    {
        return array_map(fn(DeliveryServiceNameStringEnum $enum) => $enum->name, self::cases());
    }
}
