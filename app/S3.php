<?php


namespace App;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\File;

class S3
{
    private $folderpath;

    private $s3Disk;

    /***
     * S3Manager constructor.
     * @param string $filepath
     */
    public function __construct(string $folderpath)
    {
        $this->folderpath = $folderpath;
        $this->s3Disk = Storage::disk('s3');
    }


    /***
     * S3にファイルが存在するのかを検証、存在すればtrue,存在しなければfalse
     * @param string $fileName ファイルのパス
     * @return bool
     */
    public function isFile(string $fileName): bool
    {
        //ファイルは存在するか？
        if ($this->s3Disk->exists($this->folderpath.'/'.$fileName)) return true;

        return false;
    }


    /**
     * S3にファイル保存し、保存したファイルのパスを返す
     * @param File $file ファイル本体
     * @return string
     */
    public function filUpload($file): string
    {
        //S3にファイルを保存
        $path =  'https://aitoke.s3-ap-northeast-1.amazonaws.com/' . $this->s3Disk->putFile($this->folderpath, $file, 'public');

        return $path;
    }
}
