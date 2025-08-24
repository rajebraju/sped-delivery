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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('restaurant_id')->constrained();
        $table->foreignId('delivery_man_id')->nullable()->constrained('delivery_men');
        $table->point('delivery_address'); // customer's address
        $table->enum('status', ['pending', 'assigned', 'accepted', 'rejected', 'delivered'])->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
