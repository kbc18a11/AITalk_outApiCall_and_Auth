<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api']], function () {
    //AIBOTAPIサービス関係
    Route::resource('talkText', 'NobyAPIController');

    //ユーザー登録
    Route::post('/register', 'RegisterController@create');

    //ログイン
    Route::post('login', 'AuthController@login');

    //ユーザー関係
    Route::resource('user', 'UsersController', ['only' => ['index', 'show']]);

    //Aiモデル関係
    Route::resource('aimodel', 'AiModelsController', ['only' => ['index', 'show']]);

    //Aiモデルコメント取得
    Route::get('aimodel/{ai_model_id}/aimodelcomment', 'AiModelCommentsController@show');

    //認証必須
    Route::group(['middleware' => ['jwt.auth']], function () {
        //自ユーザー情報取得
        Route::get('me', 'AuthController@me');

        //ログアウト
        Route::post('logout', 'AuthController@logout');

        //自ユーザー情報更新
        Route::resource('user', 'UsersController', ['only' => ['update']]);

        //Aiモデル関係
        Route::resource('aimodel', 'AiModelsController', ['only' => ['store', 'update']]);

        //Aiモデルのコメント関係
        Route::resource('aimodelcomment', 'AiModelCommentsController', ['only' => ['update','destroy']]);
        //Aiモデルのコメント作成
        Route::post('aimodel/{ai_model_id}/aimodelcomment', 'AiModelCommentsController@store');

        //Aiモデルのお気に入り実行
        Route::post('aimodel/{ai_model_id}/favorite','FavoriteAiModelsController@store');
        //AIモデルのお気に入り解除
        Route::delete('aimodel/favorite/{id}','FavoriteAiModelsController@destroy');
        //AIモデルのユーザーのお気に入り情報取得
        Route::get('aimodel/{ai_model_id}/favorite/user','FavoriteAiModelsController@show');
        //ユーザーのお気に入りしたAIモデルの情報一覧を習得
        Route::get('favorite','FavoriteAiModelsController@index');
    });
});
