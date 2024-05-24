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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('chat_id');
            $table->text('text');
            $table->boolean('is_read')->default(false);
            $table->enum('type', ['text', 'icon', 'file'])->default('text');
            $table->string('file_url')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('chat_id')->references('id')->on('chats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
};
