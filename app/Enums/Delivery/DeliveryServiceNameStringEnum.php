<?php

namespace App\Enums\Delivery;

enum DeliveryServiceNameStringEnum: string
{
    case NOVAPOSHTA = 'Nova Poshta';
    case UKRPOSHTA = 'Ukrposhta';
    case JUSTIN = 'Justin';

    public static function getAvailableServices(): array
    {
        return [
            self::NOVAPOSHTA,
            self::UKRPOSHTA,
            self::JUSTIN,
        ];
    }

    /**
     * @return string[]
     */
    public static function getAvailableServicesName(): array
    {
        return array_map(fn(DeliveryServiceNameStringEnum $enum) => $enum->name, self::getAvailableServices());
    }
}
