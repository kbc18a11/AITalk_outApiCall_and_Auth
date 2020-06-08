<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Aws\Polly\PollyClient;
use Aws\Credentials\Credentials;
use App\S3;
use Illuminate\Support\Facades\Validator;


class AmazonPolly extends Model
{
    //バリデーションのルール
    public $rules = [
        'text' => ['required']
    ];


    public $errorMessage = [];

    /**
     * バリデーションの検証
     * @param array
     */
    public  function getValidation(array $data)
    {
        //バリデーションの検証
        $validationResult = Validator::make($data, $this->rules);

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            $this->errorMessage['error'] = $validationResult->messages();
            return false;
        }

        return true;
    }


    /**
     * AmazonPollyのAPIにリクエストし、音声をS3に保存して、保存先のファイル名を含むパスを返す
     * @param string $text
     * @return string
     */
    public function apiRequest(string $text): string
    {
        # code...

        $credentials = new Credentials(
            env('aws_access_key_id'),
            env('aws_secret_access_key')
        );

        $client = new PollyClient([
            'region' => env('aws_default_region'),
            'version' => 'latest',
            'credentials' => $credentials
        ]);

        //音声を生成し、S3へ保存開始
        $result = $client->startSpeechSynthesisTask([
            'OutputFormat' => 'mp3', // REQUIRED
            'Text' => $text, // REQUIRED
            'TextType' => 'text',
            'VoiceId' => 'Mizuki', // REQUIRED
            'OutputS3BucketName' => env('AWS_BUCKET'),//保存先のS3のバケット名
            'OutputS3KeyPrefix' => 'voice/'//保存するフォルダ名
        ]);


        //dd($result);


        return $result['SynthesisTask']['OutputUri'];
    }
}
