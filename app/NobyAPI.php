<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class NobyAPI extends Model
{
    //APIのURL
    private static $nobyApiUrl = 'https://app.cotogoto.ai';

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
