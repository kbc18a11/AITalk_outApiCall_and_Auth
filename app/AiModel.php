<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AiModel extends Model
{
    //
    /**
     * 登録のバリデーションの条件
     * @var array
     */
    private static $createRules = [
        'name' => ['required', 'string', 'max:255', 'unique:ai_models'],
        'self_introduction' => ['string', 'max:255'],
        'open_mouth_image' => ['required', 'image'],
        'close_mouth_image' => ['required', 'image']
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
        'exists' => 'ユーザーが存在しません',
        'unique' => '既にほかのキャラクターが利用しています',
        'image' => '画像を指定してください'
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
