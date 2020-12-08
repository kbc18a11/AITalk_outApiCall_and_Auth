<?php

namespace App\Http\Controllers;

use App\AiModel;
use App\AiModelComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\S3;

class AiModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //getパラメータ user_idは存在しているか？
        if (!empty($request->user_id)) {
            return response()->json(AiModel::getPaginateData($request->user_id));
        }

        //getパラメータ user_idは存在しているか？
        if (!empty($request->serchWord)) {
            return response()->json(AiModel::getPaginateData(0,$request->serchWord));
        }

        //何も指定がなかった場合
        return response()->json(AiModel::getPaginateData());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\AiModel $aiModel
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        //idのユーザーをインスタンス化
        $aimodel = AiModel::find($id);
        if (!$aimodel) return response()->json([
            'getResult' => false,
            'error' => ['id' => '存在しないidです']
        ], 422);

        return response()->json($aimodel);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //バリデーションの検証
        $validationResult = AiModel::createValidator($request->all());
        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'createResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //口を開けた画像(open_mouth_image)の保存処理
        $s3 = new S3('aimodel/openmouthimage');
        $openMouthImagePath = $s3->filUpload($request->open_mouth_image);

        //口を閉じた画像(close_mouth_image)の保存処理
        $s3 = new S3('aimodel/closemouthimage');
        $closeMouthImagePath = $s3->filUpload($request->close_mouth_image);

        $createParam = [
            'user_id' => Auth::id(),
            'name' => $request->name,
            'self_introduction' => $request->self_introduction,
            'open_mouth_image' => $openMouthImagePath,
            'close_mouth_image' => $closeMouthImagePath
        ];
        AiModel::create($createParam);

        return response()->json([
            'createResult' => true,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $aimodel_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $aimodel_id)
    {
        //バリデーションの検証
        $validationResult = AiModel::updateValidator($request->all());
        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'updateResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //指定されたidのAIモデルをインスタンス化
        $aimodel = AiModel::find($aimodel_id);
        //インスタンス化されているか? || AIモデルのユーザーidと認可済みユーザーのidは一致しているか？
        if (!$aimodel || $aimodel->user_id !== Auth::id()){
            return response()->json([
                'deleteResult' => false,
                'error' => ['id' => '更新できないAIモデルです']
            ], 422);
        }

        //メールアドレスの検証
        if ($aimodel->otherPeopleUseName($request->name)) {
            //エラーメッセージを返す
            return response()->json([
                'updateResult' => false,
                'error' => ['name' => '既にほかのキャラクターが利用しています']
            ], 422);
        }

        //データを更新
        $aimodel->update($request->all());
        return response()->json(['updateResult' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AiModel $aiModel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $aimodel_id)
    {
        //指定されたidのAIモデルをインスタンス化
        $aimodel = AiModel::find($aimodel_id);

        //インスタンス化されているか? || AIモデルのユーザーidと認可済みユーザーのidは一致しているか？
        if (!$aimodel || $aimodel->user_id !== Auth::id()){
            return response()->json([
                'deleteResult' => false,
                'error' => ['id' => '削除できないAIモデルです']
            ], 422);
        }

        //削除の実行
        $aimodel->delete();
        return response()->json(['deleteResult' => true]);
    }
}
