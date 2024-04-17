<?php

namespace App\Loggers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class DeliveryLogger
{
    public function writeResponseResult(string $uuid, Response $response): void
    {
        Log::channel('delivery_service')->critical(sprintf(
                "DATETIME:%s\nLINE:%d\nUUID:%s\nRESPONSE STATUS:%d\nRESPONSE JSON:%s\n\n",
                now()->toDateTimeString(),
                debug_backtrace()[0]['line'],
                $uuid,
                $response->status(),
                $response->json()
            )
        );
    }

    public function writeException(string $message, int $code = 500): void
    {
        Log::channel('delivery_service')->critical(sprintf(
                "DATETIME:%s\nLINE:%d\nCODE:%d\nMESSAGE:%s\n\n",
                now()->toDateTimeString(),
                debug_backtrace()[0]['line'],
                $code,
                $message
            )
        );
    }
}
