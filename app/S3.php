<?php


namespace App;

use Illuminate\Support\Facades\Storage;

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
     * @param string $path ファイルのパス
     * @return bool
     */
    public function isFile(string $path): bool
    {
        //ファイルは存在するか？
        if ($this->s3Disk->exists($this->folderpath.'/'.$path)) return true;

        return false;
    }


    /**
     * S3にファイル保存し、保存したファイルのパスを返す
     * @param $file
     * @return string
     */
    public function filUpload($file): string
    {
        //S3にファイルを保存
        $path = $this->s3Disk->putFile($this->folderpath, $file, 'public');

        return $path;
    }
}
