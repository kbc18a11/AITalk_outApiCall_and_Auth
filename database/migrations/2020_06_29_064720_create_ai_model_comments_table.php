
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;

        class CreateAiModelCommentsTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("ai_model_comments", function (Blueprint $table) {

						$table->increments('id');
						$table->integer('ai_model_id')->unsigned();
						$table->integer('user_id')->unsigned();
						$table->string('comment');
						$table->timestamps();

						//$table->foreign("ai_model_is")->references("id")->on("ai_models");
						//$table->foreign("user_id")->references("id")->on("users");



						// ----------------------------------------------------
						// -- SELECT [ai_model_comments]--
						// ----------------------------------------------------
						// $query = DB::table("ai_model_comments")
						// ->leftJoin("ai_models","ai_models.id", "=", "ai_model_comments.ai_model_is")
						// ->leftJoin("users","users.id", "=", "ai_model_comments.user_id")
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
                Schema::dropIfExists("ai_model_comments");
            }
        }
