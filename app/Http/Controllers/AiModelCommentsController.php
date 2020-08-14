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
    public function store(Request $request)
    {
        //
        //バリデーションの検証
        $validationResult = AiModelComments::createValidator([
            'ai_model_id' => $request->ai_model_id,
            'user_id' => Auth::id(),
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
            'ai_model_id' => $request->ai_model_id,
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
        //
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
