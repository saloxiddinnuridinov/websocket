<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->text('username');
            $table->text('bio');
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('website_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('timezone')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'name' => 'Zafar',
            'surname' => 'Nizomiddinov',
            'username' => 'username',
            'bio' => 'bio',
            'address' => 'Jizzax',
            'email' => 'user@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
};
