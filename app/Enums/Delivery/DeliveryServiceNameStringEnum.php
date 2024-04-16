<?php

namespace App\Enums\Delivery;

enum DeliveryServiceNameStringEnum: string
{
    case NOWAPOSHTA = 'novaposhta';
    case UKRPOSHTA = 'ukrposhta';
    case JUSTIN = 'justin';

    static public function getAvailableServices(): array
    {
        return [
            self::NOWAPOSHTA->value,
            self::UKRPOSHTA->value,
            self::JUSTIN->value,
        ];
    }
}
