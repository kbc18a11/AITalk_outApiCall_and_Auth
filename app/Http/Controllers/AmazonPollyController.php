<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AmazonPolly;

class AmazonPollyController extends Controller
{
    /***
     * AmazonPollyのAPIにリクエストし、音声をS3に保存して、保存先のファイル名を含むパスを返す
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $amazonPolly = new AmazonPolly();

        //バリデーションの検証がだめだったか？
        if(!$amazonPolly->getValidation($request->all())){
            return response()->json($amazonPolly->errorMessage,400);
        }




        return response()->json(['voiceURL' => $amazonPolly->apiRequest($request->text)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
