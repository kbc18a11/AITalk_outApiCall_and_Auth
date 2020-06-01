<?php


namespace App;

use Illuminate\Support\Facades\Storage;

class S3
{
    private $folderpath;

    private $s3Disk;

    /***
     * S3Manager constructor.
     * @param string $folderpath
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
        if ($this->s3Disk->exists($path)) return true;

        return false;
    }
}
