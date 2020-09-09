<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
     * 更新のバリデーションの条件
     * @var array
     */
    private static $updateRules = [
        'name' => ['required', 'string', 'max:255'],
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
     * AIモデル登録のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createValidator(array $array)
    {
        # code...
        return Validator::make($array, self::$createRules, self::$errorMessages);
    }

    /**
     * AIモデル更新のパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function updateValidator(array $array)
    {
        # code...
        return Validator::make($array, self::$updateRules, self::$errorMessages);
    }

    /***
     * 引数の名前が既に以外のAIモデルに使用されているかの検証
     * @param string $name
     * @return bool nameの検証結果
     */
    public function otherPeopleUseEmail(string $name): bool
    {
        //指定されたemailを使用したカラムは存在するか？
        $aimodel = self::where('name', $name)->first();
        //既に使用されたnameか？ && 使用されているnameのAIモデルのiｄは、自分のidと同じではないか？
        return $aimodel && $aimodel->id !== $this->id;
    }

    /**
     * データの更新
     * @param array $requestBody 更新するデータ
     * @param array $options
     * @return bool
     */
    public function update(array $updateData = [], array $options = [])
    {
        //口を開けた画像(open_mouth_image)の保存処理
        $s3 = new S3('aimodel/openmouthimage');
        $openMouthImagePath = $s3->filUpload($updateData['open_mouth_image']);
        //既にある口を開けた画像(open_mouth_image)を削除
        $s3->fileDelete($this->open_mouth_image);

        //口を閉じた画像(close_mouth_image)の保存処理
        $s3 = new S3('aimodel/closemouthimage');
        $closeMouthImagePath = $s3->filUpload($updateData['close_mouth_image']);
        //既にある口を閉じた画像(close_mouth_image)を削除
        $s3->fileDelete($this->close_mouth_image);

        $attributes = [
            'name' => $updateData['name'],
            'self_introduction' => $updateData['self_introduction'],
            'open_mouth_image' => $openMouthImagePath,
            'close_mouth_image' => $closeMouthImagePath
        ];

        return parent::update($attributes, $options);
    }


    /**
     * ページネーション用のデータを取得
     * @param int $user_id ユーザーid
     * @param string $serchWord　検索ワード (name,self_introductionを対象)
     * @return mixed
     */
    public static function getPaginateData(int $user_id = 0, string $serchWord = '')
    {

        $query = self::query();

        //ユーザーidの指定はあるか？
        if ($user_id) {
            //ユーザーidが一致しているものを検索
            $query->where('user_id', $user_id);
        }

        //検索の指定はあるか？
        if ($serchWord) {
            //名前が部分一致しているものを検索
            $query->where('name', 'like', "%$serchWord%");
            //自己紹介文が部分一致しているものを検索
            $query->orWhere('self_introduction', 'like', "%$serchWord%");
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
        $paginateNumber = 5;//ページネーションで取得する個数

        //コメントの情報とそのコメントをしたユーザーの情報を取得
        return AiModelComments::select(['ai_model_comments.id', 'ai_model_comments.comment', 'ai_model_comments.created_at',
            'ai_model_comments.user_id', 'users.name', 'users.icon'])
            ->join('users', 'ai_model_comments.user_id', '=', 'users.id')
            ->where('ai_model_comments.ai_model_id', $this->id)
            ->orderBy('ai_model_comments.created_at', 'desc')
            ->paginate($paginateNumber);

    }

    /**
     * 自分のレコードや外部参照されているレコードを削除する
     * @throws \Exception
     */
    public function deleteMe()
    {
        //対象のAIモデルのコメントを削除
        AiModelComments::deleteByAiModel_id($this->id);
        //対象のAIモデルのいいねを解除
        FavoriteAiModel::deleteByAiModel_id($this->id);

        //自分のレコードを削除
        $this->delete();
    }
}
