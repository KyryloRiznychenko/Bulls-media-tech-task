<?php

use App\Http\Controllers\Delivery\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => response()->json(['message' => 'Success']));

Route::prefix('delivery')->name('delivery.')->group(function () {
    Route::post('send', [DeliveryController::class, 'sendPackage'])->name('send');
});
