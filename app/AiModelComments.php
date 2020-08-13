<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AiModelComments extends Model
{
    //
    /**
     * 登録のバリデーションの条件
     * @var array
     */
    private static $createRules = [
        'ai_model_id' => ['required','integer','exists:ai_model_is,id','unique:'],
    ];

    /**
     * エラーメッセージ一覧
     * @var array
     */
    private static $errorMessages = [
        'required' => '必須項目です。',
        'integer' => '数値を入力してください',
        'string' => '文字を入力してください',
        'max' => '255文字以下入力してください',
        'unique' => '既にほかのキャラクターが利用しています',
    ];

    /**
     * ユーザー登録のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createValidator(array $array)
    {
        # code...
        return Validator::make($array, self::$createRules, self::$errorMessages);
    }
}
