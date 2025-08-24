<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['radius', 'polygon']);
            $table->decimal('radius_km', 5, 2)->nullable();
            $table->decimal('center_lat', 10, 8)->nullable(); 
            $table->decimal('center_lng', 11, 8)->nullable(); 
            $table->polygon('area')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_zones');
    }
};
