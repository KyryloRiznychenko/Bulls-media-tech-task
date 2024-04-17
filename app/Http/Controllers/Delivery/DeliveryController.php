<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Delivery\SendPackageRequest;
use App\Services\Delivery\DeliveryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

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

        try {
            $isSend = $this->deliveryService->sendPackage(
                Arr::except($validated, ['delivery_service_name', 'delivery_address_to']),
                $validated['delivery_service_name'],
                $validated['delivery_address_to']
            );

            if (!$isSend) {
                throw new Exception(__('delivery.errors.plug'), Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            throw new JsonException($e->getMessage(), $e->getCode());
        }

        return response()->json(['message' => 'Success']);
    }
}
