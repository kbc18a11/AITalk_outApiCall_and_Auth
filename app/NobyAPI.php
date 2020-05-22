<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class NobyAPI extends Model
{
    //APIのURL
    private $nobyApiUrl = 'https://app.cotogoto.ai';



    public function apiReqest()
    {
        # code...
        //URL

        //Clientインスタンス化
        $client = new Client([
            'base_uri' => $this->nobyApiUrl,
        ]);

        $path = '/webapi/noby.json?appkey=ca6bb3e3a9a11cf0ba890cd350054eb8&mail=wada121400@gmail.com&pass=Tabunnsoreha07-&text=今日も1日頑張りましょう。&lat=&lng=&study=&persona=&ending=';
        $res = $client->request(
            'GET',
            $path,
            [
                'allow_redirects' => true,
            ]
        );
    }
}
