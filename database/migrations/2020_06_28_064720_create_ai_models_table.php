<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAiModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ai_models", function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name')->unique();
            $table->string('self_introduction')->nullable(); //自己紹介文
            $table->string('open_mouth_image'); //口が開いている顔
            $table->string('close_mouth_image'); //口が閉じている顔
            $table->timestamps();


            //*********************************
            // Foreign KEY [ Uncomment if you want to use!! ]
            //*********************************
            $table->foreign("user_id")->references("id")->on("users");


            // ----------------------------------------------------
            // -- SELECT [ai_models]--
            // ----------------------------------------------------
            // $query = DB::table("ai_models")
            // ->leftJoin("users","users.id", "=", "ai_models.user_id")
            // ->get();
            // dd($query); //For checking


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("ai_models");
    }
}
