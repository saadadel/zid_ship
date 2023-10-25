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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("courier_id")->constrained();

            $table->string("consignor_name");
            $table->string("consignor_email");
            $table->string("consignor_phone");
            $table->string("consignee_name");
            $table->string("consignee_email");
            $table->string("consignee_phone");

            $table->dateTime("due_date")->nullable();
            $table->dateTime("actual_delivery_date");
            $table->foreignId("pickup_address_id")->constrained("addresses", "id");
            $table->foreignId("delivery_address_id")->constrained("addresses", "id");

            // Dimensions
            $table->unsignedFloat("length");
            $table->unsignedFloat("width");
            $table->unsignedFloat("height");
            $table->string("dimensions_unit", 10)->default("cm");

            $table->unsignedFloat("weight");
            $table->string("weight_unit", 10)->default("g");

            $table->unsignedInteger("num_of_pieces");

            $table->string("products_type");
            $table->string("payment_type");
            $table->string("description", 500)->nullable();
            $table->string("origin_country_code", 3);

            $table->string("status", 25);

            $table->string("shipment_num")->unique();
            $table->unsignedFloat("coast");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};