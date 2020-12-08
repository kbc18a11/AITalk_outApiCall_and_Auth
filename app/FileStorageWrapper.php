<?php


namespace App;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\File;

class FileStorageWrapper
{
    //対象とするS3フォルダ
    private $folderPath;

    //S3のインスタンス
    private $Disk;

    /***
     * S3Manager constructor.
     * @param string $folderPath
     */
    public function __construct(string $folderPath)
    {
        $this->folderPath = $folderPath;
        $this->Disk = Storage::disk('s3');
    }


    /***
     * S3にファイルが存在するのかを検証、存在すればtrue,存在しなければfalse
     * @param string $filePath ファイルのパス
     * @return bool
     */
    public function isFile(string $filePath): bool
    {
        //ファイルは存在するか？
        if ($this->Disk->exists($filePath)) return true;

        return false;
    }

    /**
     * 対象のファイルを削除
     * @param $filePath
     */
    public function fileDelete($filePath)
    {
        $this->Disk->delete($filePath);
    }

    /**
     * S3にファイル保存し、保存したファイルのパスを返す
     * @param File $file ファイル本体
     * @return string
     */
    public function filUpload($file): string
    {
        //S3にファイルを保存
        $path =  $this->Disk->putFile($this->folderPath, $file, 'public');

        return $path;
    }
}
