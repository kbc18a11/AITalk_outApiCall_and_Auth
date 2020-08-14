<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AiModelComments extends Model
{
    //
    /**
     * 保存を操作するカラム
     * @var array
     */
    protected $fillable = [
        'ai_model_id', 'user_id', 'comment'
    ];

    /**
     * 登録のバリデーションの条件
     * @var array
     */
    private static $createRules = [
        'ai_model_id' => ['required', 'integer', 'exists:ai_models,id'],
        'comment' => ['required', 'max:255']
    ];

    /**
     * 更新のバリデーションの条件
     * @var array
     */
    private static $updateRules = [
        'comment' => ['required', 'max:255']
    ];

    /**
     * エラーメッセージ一覧
     * @var array
     */
    private static $errorMessages = [
        'required' => '必須項目です。',
        'exists' => '存在しないAiモデルです。',
        'integer' => '数値を入力してください',
        'string' => '文字を入力してください',
        'max' => '255文字以下で入力してください'
    ];

    /**
     * コメント作成のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createValidator(array $array)
    {
        # code...
        return Validator::make($array, self::$createRules, self::$errorMessages);
    }

    /**
     * コメント内容更新のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function updateValidator(array $array)
    {
        # code...
        return Validator::make($array, self::$updateRules, self::$errorMessages);
    }
}
