<?php

namespace App\Http\Requests\Delivery;

use App\Enums\Delivery\DeliveryServiceNameStringEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|min:2|max:60',
            'customer_phone_number' => 'required|string|min:9|max:15',
            'customer_email' => 'required|email|max:255',

            'package_width' => 'required|numeric|min:0.1|regex:/^\d+(\.\d{1,2})?$/',
            'package_height' => 'required|numeric|min:0.1|regex:/^\d+(\.\d{1,2})?$/',
            'package_length' => 'required|numeric|min:0.1|regex:/^\d+(\.\d{1,2})?$/',
            'package_weight' => 'required|numeric|min:0.1|regex:/^\d+(\.\d{1,2})?$/',

            'delivery_service_name' => [
                'required',
                'string',
                Rule::in(DeliveryServiceNameStringEnum::getAvailableServicesName())
            ],
            'delivery_address_to' => 'required|string|min:10|max:255',
        ];
    }

    public function prepareForValidation(): void
    {
        // Setup by default Nova Poshta
        if (request()->isEmptyString('delivery.service_name')) {
            $this->merge(['delivery_service_name' => DeliveryServiceNameStringEnum::NOVAPOSHTA->name]);
        }
    }

    public function messages(): array
    {
        return ['delivery.service_name.in' => __('delivery.errors.invalid_service_name')];
    }
}
