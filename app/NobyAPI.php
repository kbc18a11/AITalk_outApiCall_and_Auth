<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Support\Facades\Validator;

class NobyAPI extends Model
{
    //APIのURL
    private static $nobyApiUrl = 'https://app.cotogoto.ai';

    public static $apiReqestRules = [
        'text' => ['required']
    ];

    /**
     * APIリクエストのパラメータのバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createvalidator(array $array)
    {
        # code...
        return Validator::make($array, NobyAPI::$apiReqestRules);
    }


    /**
     * APIにリクエストして、結果を返す
     * @param string $text
     */
    public function apiReqest(string $text)
    {
        # code...


        //Clientインスタンス化
        $client = new Client([
            'base_uri' => $this->nobyApiUrl,
        ]);

        //リクエストパラメータを含んだURL
        $path = 'https://app.cotogoto.ai/webapi/noby.json?appkey=' . env('NODY_API_APPKEY') . '&mail=' . env('NODY_API_MAIL') . '&pass=' . env('NODY_API_PASS') . '&text=' . $text . '&lat=&lng=&study=&persona=&ending=';

        //リクエスト開始
        $res = $client->request(
            'GET',
            $path,
            [
                'allow_redirects' => true,
            ]
        );

        return $res;
    }
}
