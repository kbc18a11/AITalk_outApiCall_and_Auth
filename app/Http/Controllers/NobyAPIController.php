<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NobyAPI;

class NobyAPIController extends Controller
{
    /**
     *　APIサービスにリクエスト
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //バリデーションの検証
        $validationResult = NobyAPI::getValidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['error'] = $validationResult->messages();
            return response()->json($param,);
        }

        $nobyApi = new NobyAPI();
        //APIにリクエスト
        $res = $nobyApi->apiReqest($request->text);

        return response(json_decode($res->getBody()->getContents(), true));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
