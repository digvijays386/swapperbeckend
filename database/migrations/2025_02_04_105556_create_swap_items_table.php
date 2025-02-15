<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwapItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swap_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swap_id')->constrained('swaps')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->enum('type', ['offered', 'requested']);
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
        Schema::dropIfExists('swap_items');
    }
}
