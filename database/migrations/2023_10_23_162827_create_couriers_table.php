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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("phone")->unique();
            $table->string("description");
            $table->string("demo_url");
            $table->string("live_url");
            $table->json("credentials");
            $table->json("supported_product_types");
            $table->unsignedInteger("current_shipments")->default(0);
            $table->unsignedInteger("max_shipments")->default(1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};