<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIveriResultTable extends Migration
{
    public function up()
    {
        Schema::create('iveri_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount')->nullable();
            $table->string('application_id', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->string('electronic_commerce_indicator', 255)->nullable();
            $table->string('expiry_date', 255)->nullable();
            $table->text('jwt')->nullable();
            $table->string('merchant_data', 255)->nullable();
            $table->string('merchant_reference', 255)->unique()->nullable();
            $table->string('pan', 255)->nullable();
            $table->string('result_code', 255)->nullable();
            $table->string('result_description', 255)->nullable();
            $table->string('three_d_secure_request_id', 255)->nullable();
            $table->string('three_d_secure_veres_enrolled', 255)->nullable();
            $table->string('card_security_code', 255)->nullable();
            $table->text('result_response')->nullable();
            $table->string('three_d_secure_ds_trans_id', 255)->nullable();
            $table->string('card_holder_authentication_data', 255)->nullable();
            $table->string('card_holder_authentication_id', 255)->nullable();
            $table->string('encrypted_pan', 255)->nullable();
            $table->text('result_response_auth')->nullable();
            // no timestamps as none provided
        });
    }

    public function down()
    {
        Schema::dropIfExists('iveri_result');
    }
}
