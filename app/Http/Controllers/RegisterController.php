<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

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
            return response()->json([
                'createResult' => false,
                'error' => $validationResult->messages()
            ]);
        }

        //ユーザー登録
        $createparam = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'icon' => 'https://aitoke.s3-ap-northeast-1.amazonaws.comicon/default/default_icon.png',
        ];

        //INSERT文実行
        User::create($createparam);

        return response()->json([
            'createResult' => true,
        ]);
    }
}
