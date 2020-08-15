<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteAiModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("favorite_ai_models", function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->biginteger('user_id')->unsigned();
            $table->biginteger('ai_model_id')->unsigned();
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("ai_model_id")->references("id")->on("ai_models");


            // ----------------------------------------------------
            // -- SELECT [favorite_ai_models]--
            // ----------------------------------------------------
            // $query = DB::table("favorite_ai_models")
            // ->leftJoin("users","users.id", "=", "favorite_ai_models.user_id")
            // ->leftJoin("ai_models","ai_models.id", "=", "favorite_ai_models.ai_model_id")
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
        Schema::dropIfExists("favorite_ai_models");
    }
}
