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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('time');
            $table->string('venue');
            $table->decimal('early_bird_ticket', 8, 2);
            $table->decimal('advance_ticket', 8, 2);
            $table->decimal('gate_ticket', 8, 2);
            $table->decimal('vip_ticket', 8, 2);
            $table->decimal('vvip_ticket', 8, 2);
            $table->string('special_code')->unique()->nullable(); // special code, unique and optional
            $table->string('event_image_url')->unique()->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
