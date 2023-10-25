<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string("country_code", 3);
            $table->string("post_code", 15);
            $table->string("state_code", 15)->nullable();
            $table->foreignId("city_id")->constrained();
            $table->string("line1", 100);
            $table->string("line2", 100)->nullable();
            $table->string("line3", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};