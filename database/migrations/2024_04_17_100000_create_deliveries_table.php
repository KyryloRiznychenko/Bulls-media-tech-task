<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('customer_name');
            $table->string('customer_phone_number');
            $table->string('customer_email');

            $table->decimal('package_width');
            $table->decimal('package_height');
            $table->decimal('package_length');
            $table->decimal('package_weight');

            $table->string('delivery_service_name');
            $table->string('delivery_address_from');
            $table->string('delivery_address_to');

            // I use it here to check an unsuccessful/successful case.
            $table->boolean('is_send_successful')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
