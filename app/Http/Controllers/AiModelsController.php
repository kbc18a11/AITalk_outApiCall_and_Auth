<?php

namespace App\Http\Controllers;

use App\AiModel;
use Illuminate\Http\Request;

class AiModelsController extends Controller
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

        return response()->json(['aa' => 'aa']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\AiModel $aiModel
     * @return \Illuminate\Http\Response
     */
    public function show(AiModel $aiModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\AiModel $aiModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AiModel $aiModel)
    {
        //
        return response()->json(['aa' => 'aa']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AiModel $aiModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(AiModel $aiModel)
    {
        //
    }
}
