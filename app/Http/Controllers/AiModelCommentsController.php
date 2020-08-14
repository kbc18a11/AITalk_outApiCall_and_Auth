<?php

namespace App\Http\Controllers;

use App\AiModelComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiModelCommentsController extends Controller
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
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id)
    {
        //
        //バリデーションの検証
        $validationResult = AiModelComments::createValidator([
            'ai_model_id' => $id,
            'comment' => $request->comment
        ]);
        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'createResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }
        //コメントを保存
        $createParam = [
            'ai_model_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ];
        AiModelComments::create($createParam);
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

    }

    public function update(Request $request, int $id)
    {
        $aiModelComments = AiModelComments::find($id);
        //指定したidのコメントは存在していなか？
        // &&　指定したコメントのuserとリクエストしたユーザーとのidは違うか？
        if (!$aiModelComments
            || $aiModelComments->user_id !== Auth::id()) {
            return response()->json([
                'updateResult' => false,
                'error' => ['id' => '更新できないコメントです']
            ], 422);
        }

        //バリデーションの検証
        $validationResult =
            AiModelComments::updateValidator($request->all());
        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            return response()->json([
                'updateResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //コメントの更新を実行
        $aiModelComments->update($request->all());
        return response()->json(['createResult' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
