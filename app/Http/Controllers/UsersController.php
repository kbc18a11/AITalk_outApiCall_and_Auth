<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //idのユーザーをインスタンス化
        $user = User::find($id);
        if (!$user) return response()->json([
            'createResult' => false,
            'error' => ['id' => '存在しないユーザーidです']
        ], 422);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //更新対象のユーザーとリクエストしたユーザーとのidは違うか？
        if (intval($id) !== Auth::id()) {
            return response()->json([
                'updateResult' => false,
                'error' => ['id' => '更新できないユーザーです']
            ], 422);
        }

        //バリデーションの検証
        $validationResult = User::updateValidator($request->all());
        //エラーは存在するか？
        if ($validationResult->fails()) {
            //エラーメッセージを返す
            return response()->json([
                'updateResult' => false,
                'error' => $validationResult->messages()
            ], 422);
        }

        //userをインスタンス化
        $user = User::find($id);
        //メールアドレスの検証
        if ($user->otherPeopleUseEmail($request->email)) {
            //エラーメッセージを返す
            return response()->json([
                'updateResult' => false,
                'error' => ['email' => '既にほかのユーザーが利用しているメールアドレスです']
            ], 422);
        }

        return response()->json($user->otherPeopleUseEmail($request->email));
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
