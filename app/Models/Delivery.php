<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'customer_name',
        'customer_phone_number',
        'customer_email',

        'package_width',
        'package_height',
        'package_length',
        'package_weight',

        'delivery_service_name',
        'delivery_address_from',
        'delivery_address_to',

        'is_send_successful',
    ];

    protected $casts = [
        'is_send_successful' => 'boolean',
    ];
}
