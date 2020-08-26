<?php

namespace App\Http\Controllers;

use App\FavoriteAiModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteAiModelsController extends Controller
{
    /**
     * ユーザーのお気に入り情報一覧を習得
     *　user/aimodel/favorite
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //ユーザーをインスタンス化
        $user = User::find(Auth::id());
        //お気に入り登録の情報を取得
        $favoriteData = $user->getFavoriteAiModelData();

        return response()->json(['getResult' => true, 'favoriteData' => $favoriteData]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param int $ai_model_id AIモデルのid
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(int $ai_model_id, Request $request)
    {
        //バリエーションの検証用データ構築
        $validationData = $request->all();
        $validationData['ai_model_id'] = $ai_model_id;
        $validationData['user_id'] = Auth::id();

        //バリデーションの検証
        $validationResult = FavoriteAiModel::createValidator($validationData);
        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'createResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //いいね登録
        $createParam = [
            'ai_model_id' => $ai_model_id,
            'user_id' => Auth::id()
        ];
        //insertを実行し、保存場を取得
        $favoriteData = FavoriteAiModel::create($createParam);

        return response()->json(['createResult' => true, 'favoriteData' => $favoriteData]);
    }


    /**
     * AIモデルにたいするお気に入り情報を習得
     * /aimodel/{ai_model_id}/favorite/user
     *
     * @param int $ai_model_id 対象のAIモデルのid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $ai_model_id)
    {
        //バリデーションの検証
        $validationResult = FavoriteAiModel
            ::getUserFavorValidator(['ai_model_id' => $ai_model_id]);

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'getResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //ユーザーをインスタンス化
        $user = User::find(Auth::id());
        //お気に入り登録の情報を取得
        $favoriteData = $user->getRegisterFavoriteAiModel($ai_model_id)[0];

        return response()->json(['getResult' => true, 'favoriteData' => $favoriteData]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id いいねのid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        //idをもとにインスタンス化
        $favoriteAiModel = FavoriteAiModel::find($id);
        //インスタンス化されているか？
        // || ユーザーidと利用ユーザーidは一致してないか？
        if (!$favoriteAiModel ||
            $favoriteAiModel->user_id !== Auth::id()) {
            # code...
            return response()->json([
                'deleteResult' => false,
                'error' => ['id' => 'いいねを解除できません']
            ], 422);
        }

        $favoriteAiModel->delete();
        return response()->json(['deleteResult' => true]);
    }
}
