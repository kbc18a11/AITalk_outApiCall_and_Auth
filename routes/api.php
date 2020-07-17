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
    //未認証ユーザーの飛ばす先
    Route::get('/', function () {
        return response()->json(['error' => 'unauthorized'], 401);
    })->name('login');

    //AIBOTAPIサービス関係
    Route::resource('talkText', 'NobyAPIController');

    Route::resource('talkVoice', 'AmazonPollyController');

    //ユーザー登録
    Route::post('/register', 'RegisterController@create');

    //ログイン
    Route::post('login', 'AuthController@login');

    //ユーザー関係
    Route::resource('user', 'UsersController', ['only' => ['index', 'show']]);

    //認証必須
    Route::group(['middleware' => ['jwt.auth']], function () {
        //自ユーザー情報取得
        Route::get('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');

        Route::resource('user', 'UsersController', ['only' => ['update']]);
    });
});
