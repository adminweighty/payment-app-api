<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('iveri_credentials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('iveri_application_id', 255)->nullable();
            $table->string('iveri_certificate_id', 255)->nullable();
            $table->timestamp('iveri_created_at')->nullable();
            $table->string('iveri_merchant_profile_id', 255)->nullable();
            $table->timestamp('iveri_updated_at')->nullable();
            $table->string('iveri_base_url', 255)->nullable();
            $table->string('iveri_credential_type_name', 255)->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('iveri_credentials');
    }
};
