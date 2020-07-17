<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AiModel extends Model
{
    //
    /**
     * 登録のバリデーションの条件
     * @var array
     */
    private static $createRules = [
        'user_id' => ['required', 'exists:users,id'],
        'name' => ['required', 'string', 'max:255', 'unique:ai_models'],
        'self_introduction' => ['string', 'max:255'],
        'open_mouth_image' => ['required', 'image'],
        'close_mouth_image' => ['required', 'image']
    ];
}
