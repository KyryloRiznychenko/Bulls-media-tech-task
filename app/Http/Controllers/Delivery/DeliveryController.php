<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Delivery\SendPackageRequest;
use App\Services\Delivery\DeliveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use JsonException;

class DeliveryController extends Controller
{
    public function __construct(private readonly DeliveryService $deliveryService)
    {
    }

    /**
     * @throws JsonException
     */
    public function sendPackage(SendPackageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $isSend = $this->deliveryService->sendPackage(
            Arr::except($validated, ['delivery_service_name', 'delivery_address_to']),
            $validated['delivery_service_name'],
            $validated['delivery_address_to']
        );

        if (!$isSend) {
            throw new JsonException(__('delivery.errors.plug'));
        }

        return response()->json(['message' => 'Success']);
    }
}
