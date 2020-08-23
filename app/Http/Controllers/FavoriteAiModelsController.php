<?php

namespace App\Http\Controllers;

use App\FavoriteAiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteAiModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        FavoriteAiModel::create($createParam);

        return response()->json(['createResult' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
