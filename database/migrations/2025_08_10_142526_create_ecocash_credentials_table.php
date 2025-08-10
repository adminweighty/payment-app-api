<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcocashCredentialsTable extends Migration
{
    public function up()
    {
        Schema::create('ecocash_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ecocash_base_url', 255)->nullable()->comment('base url for ecocash endpoint');
            $table->string('ecocash_api_key', 255)->nullable()->comment('Api key for the payment api.');
            $table->string('ecocash_payment_endpoint', 255)->nullable();
            $table->string('ecocash_transaction_status_endpoint', 255)->nullable();
            $table->timestamp('ecocash_created_at')->nullable();
            $table->string('ecocash_reversal_endpoint', 255)->nullable();
            $table->string('ecocash_transaction_merchant_code', 255)->nullable();
            $table->string('ecocash_transaction_merchant_number', 255)->nullable();
            $table->string('ecocash_transaction_merchant_pin', 255)->nullable();
            $table->string('ecocash_transaction_password', 255)->nullable();
            $table->string('ecocash_transaction_username', 255)->nullable();
            $table->timestamp('ecocash_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecocash_credentials');
    }
}
