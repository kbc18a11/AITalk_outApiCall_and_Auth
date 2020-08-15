<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("follows", function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->biginteger('user_id')->unsigned(); //フォローをしているユーザー
            $table->biginteger('follow_user_id')->unsigned(); //フォローを受けるユーザー
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("follow_user_id")->references("id")->on("users");


            // ----------------------------------------------------
            // -- SELECT [follows]--
            // ----------------------------------------------------
            // $query = DB::table("follows")
            // ->leftJoin("users","users.id", "=", "follows.user_id")
            // ->leftJoin("users","users.id", "=", "follows.follow_user_id")
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
        Schema::dropIfExists("follows");
    }
}
