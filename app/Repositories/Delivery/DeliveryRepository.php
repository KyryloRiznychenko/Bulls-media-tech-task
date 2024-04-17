<?php

namespace App\Repositories\Delivery;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class DeliveryRepository
{
    /**
     * @throws JsonException
     */
    public function store(array $inputData): Delivery
    {
        DB::beginTransaction();
        try {
            $delivery = Delivery::create($inputData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new JsonException(
                "{$e->getLine()} \n{$e->getMessage()}",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $delivery;
    }

    /**
     * @throws JsonException
     */
    public function update(string $uuid, array $inputData)
    {
        DB::beginTransaction();
        try {
            $delivery = Delivery::query()->whereUuid($uuid)->first();
            $delivery->update($inputData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new JsonException(
                "{$e->getLine()} \n{$e->getMessage()}",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $delivery;
    }
}
