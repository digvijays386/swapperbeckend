<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_swap_id');
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('reciever_id')->unsigned();
            $table->string('chat_id');
            $table->integer('is_accepted')->default(0);
            $table->integer('status')->default(0);
            // $table->foreign('user_swap_id')->references('id')->on('user_swap')->onDelete('cascade');
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
        Schema::dropIfExists('messages');
    }
}
