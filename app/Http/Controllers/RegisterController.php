<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegisterController extends Controller
{
    //
    /**
     * ユーザー登録
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        //バリデーションの検証
        $validationResult = User::createValidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        return response()->json(['a']);
    }
}
