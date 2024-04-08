<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSwapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_swap', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender_id');
            $table->bigInteger('reciever_id');
            $table->unsignedBigInteger('sender_product_id');
            $table->unsignedBigInteger('reciever_product_id');
            $table->foreign('sender_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('reciever_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('user_swap');
    }
}
