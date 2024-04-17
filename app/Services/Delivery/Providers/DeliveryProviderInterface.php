<?php

namespace App\Services\Delivery\Providers;

use Illuminate\Http\Client\Response;

interface DeliveryProviderInterface
{
    /**
     * @param  array{
     *     customer_name:string,
     *     customer_phone_number:string,
     *     customer_email:string,
     *     package_width:numeric,
     *     package_heigth:numeric,
     *     package_length:numeric,
     *     package_weight:numeric,
     *     ...
     * }  $inputData
     * @param  array{
     *     from:string,
     *     to:string
     * }  $addresses
     * @return Response
     */
    public function sendPackage(array $inputData, array $addresses): Response;
}
