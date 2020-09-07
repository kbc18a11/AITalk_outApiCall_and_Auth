<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class FavoriteAiModel extends Model
{
    /**
     * 保存を操作するカラム
     * @var array
     */
    protected $fillable = [
        'user_id', 'ai_model_id'
    ];

    /**
     * エラーメッセージ一覧
     * @var array
     */
    private static $errorMessages = [
        'required' => '必須項目です。',
        'exists' => '存在しないAiモデルです。',
        'unique' => '既にお気に入り登録しています'
    ];

    private static $getUserFavoriteRules = [
        'ai_model_id' => ['required','exists:ai_models,id']
    ];

    /**
     * 登録のバリデーションの条件
     * @param int $ai_model_id
     * @param int $user_id
     * @return array
     */
    public static function createRules(int $ai_model_id, int $user_id)
    {
        return ['ai_model_id' => [
            'required',
            'exists:ai_models,id',
            Rule::unique((new self)->getTable())
                ->where('ai_model_id', $ai_model_id)
                ->where('user_id', $user_id),
        ]];
    }

    /**
     * ユーザーのお気に入り取得のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function getUserFavorValidator(array $array)
    {
        # code...
        return Validator::make(
            $array, self::$getUserFavoriteRules,
            self::$errorMessages
        );
    }

    /**
     * 登録のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createValidator(array $array)
    {
        # code...
        return Validator::make(
            $array, self::createRules($array['ai_model_id'], $array['user_id']),
            self::$errorMessages
        );
    }

    /**
     * 引数で指定されたAIモデルのいいねをまとめて解除する
     * @param int $ai_model_id
     */
    public static function deleteByAiModel_id(int $ai_model_id)
    {
        $query = self::query();
        //指定されたAIモデルにしてるいいねをすべて解除
        $query->where('ai_model_id',$ai_model_id)->delete();
    }
}
