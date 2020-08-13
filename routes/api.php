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

    Route::resource('talkVoice', 'AmazonPollyController');

    //ユーザー登録
    Route::post('/register', 'RegisterController@create');

    //ログイン
    Route::post('login', 'AuthController@login');

    //ユーザー関係
    Route::resource('user', 'UsersController', ['only' => ['index', 'show']]);

    //Aiのキャラクター関係
    Route::resource('aimodel', 'AiModelsController', ['only' => ['index', 'show']]);

    //認証必須
    Route::group(['middleware' => ['jwt.auth']], function () {
        //自ユーザー情報取得
        Route::get('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');

        Route::resource('user', 'UsersController', ['only' => ['update']]);

        //Aiのキャラクター関係
        Route::resource('aimodel', 'AiModelsController', ['only' => ['store', 'update']]);

        //Aiモデルのコメント関係
        //Route::resource('aimodelcomment', 'AiModelCommentsController', ['only' => ['destroy']]);
        //コメント作成
        Route::post('aimodelcomment/{id}', 'AiModelCommentsController@store');

    });
});
