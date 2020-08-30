<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AiModel extends Model
{
    /**
     * 保存を操作するカラム
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'self_introduction',
        'open_mouth_image', 'close_mouth_image'
    ];


    /**
     * 登録のバリデーションの条件
     * @var array
     */
    private static $createRules = [
        'name' => ['required', 'string', 'max:255', 'unique:ai_models'],
        'self_introduction' => ['max:255'],
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

    /**
     * ページネーション用のデータを取得
     * @param int $user_id ユーザーid
     * @return mixed
     */
    public static function getPaginateData(int $user_id = 0)
    {
        $query = self::query();

        //ユーザーidの指定はあるか？
        if ($user_id) {
            //ユーザーidが一致しているものを検索
            $query->where('user_id', $user_id);
        }

        $paginateNumber = 5;//ページネーションで取得する個数
        return $query->orderBy('updated_at', 'desc')->paginate($paginateNumber);
    }

    /**
     * idからAiModelCommentsの外部キー（ai_model_id）のコメントを取得
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getComments()
    {
        DB::enableQueryLog();
        $paginateNumber = 5;//ページネーションで取得する個数

        //コメントの情報とそのコメントをしたユーザーの情報を取得
        return AiModelComments::select(['ai_model_comments.id', 'ai_model_comments.comment', 'ai_model_comments.created_at',
            'ai_model_comments.user_id', 'users.name', 'users.icon'])
            ->join('users', 'ai_model_comments.user_id', '=', 'users.id')
            ->where('ai_model_comments.ai_model_id', $this->id)
            ->orderBy('ai_model_comments.created_at', 'desc')
            ->paginate($paginateNumber);

        return DB::getQueryLog();
    }
}
