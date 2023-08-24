<?php

namespace core\upload;

use core\upload\exception\UploadException;

//上传文件
class Upload
{
    protected int $maxSize = 2; // M
    protected array $types = [
        'image/png','image/jpeg',"image/gif","image/jpg"
    ];

    //上传目录
    protected string $uploadDir = '';

    //设置上传文件大小
    public function setMaxSize(int $size)
    {
        $this->maxSize = $size;

        return $this;
    }

     //设置上传类型
    public function setTypes(array $types)
    {
        $this->types = $types;
        return $this;
    }

    //设置文件保存目录
    public function setUploadDir(string $dir)
    {
        $this->uploadDir  = $dir;
        return $this;
    }

    //上傳到本地
    public function loacal(array $file)
    {
        if (empty($file)) {
            throw new UploadException('沒有獲取儅上傳的文件信息');
        }
        //判斷是否上传成功
        if ($file['error'] != 0) {
            throw new UploadException('文件上传失败');
        }
        //判断大小
        if ($file['size'] > $this->maxSize * 1024 * 1024) {
            throw new UploadException('上传文件不大于'.$this->maxSize."M");
        }

        //文件类型
        if (!in_array($file['type'],$this->types)){
            throw new UploadException('不支持上传该文件类型');
        }
        $nameArr = explode(".",$file['name']);
        $suffix = $nameArr[count($nameArr) - 1];

        $fullPath = $this->createFileName($suffix);
        $result = move_uploaded_file($file['tmp_name'],$fullPath);
        if (!$result){
            throw new UploadException('文件上传失败');
        }
        return str_replace("\\",'/',str_replace(ROOT_PATH,'',$fullPath));
   }

   //创建文件
   protected function createFileName(string $suffix)
   {
       $dir = '';
       if ($this->uploadDir){
           $dir =  $this->uploadDir;
       }
       $dir  = "public/uploads/".$dir . DS .date("Y/m/d");

       $fullDir = ROOT_PATH .DS .$dir;
       if (!file_exists($fullDir)){
           mkdir($fullDir,0777,true);
       }
       $fileName = md5(uniqid()).".{$suffix}";
       return $fullDir . DS .$fileName;

   }
}