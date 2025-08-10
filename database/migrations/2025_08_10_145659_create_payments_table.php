<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method_name')->nullable();
            $table->double('payment_amount')->nullable();
            $table->string('payment_description')->nullable();
            $table->integer('payer_paid_status')->default(0);
            $table->boolean('status')->nullable();
            $table->string('merchant_reference')->nullable();
            $table->string('payer_names')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_mobile')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_status_one')->nullable();
            $table->string('payment_status_two')->nullable();
            $table->integer('payer_number_of_tickets')->nullable();
            $table->string('payment_currency')->nullable();
            $table->integer('payment_special_code')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
