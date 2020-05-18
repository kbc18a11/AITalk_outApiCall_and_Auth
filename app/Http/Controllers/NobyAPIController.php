<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NobyAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //URL
        $nobyApiUrl = 'https://app.cotogoto.ai';

        //Clientインスタンス化
        $client = new Client([
            'base_uri' => $nobyApiUrl,
        ]);

        $path = '/webapi/noby.json?appkey=ca6bb3e3a9a11cf0ba890cd350054eb8&mail=wada121400@gmail.com&pass=Tabunnsoreha07-&text=今日も1日頑張りましょう。&lat=&lng=&study=&persona=&ending=';
        $res = $client->request(
            'GET',
            $path,
            [
                'allow_redirects' => true,
            ]
        );

        return response(json_decode($res->getBody()->getContents(), true));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
