<?php

namespace core\common\request;

class Request
{
    //獲取post數據
    public function post(string $key = ''): array|string|null
    {
        if ( ! empty($key)) {
            return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : null;
        } else {
            return $this->array_to_html_xss($_POST);
        }
    }

    //獲取get請求參數
    public function get(string $key = ''): array|string|null
    {
        if ( ! empty($key)) {
            return isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : null;
        } else {
            return $this->array_to_html_xss($_GET);
        }
    }


    //數組產品 xss 轉換
    public function array_to_html_xss(array $data): array
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $result[$key] = $this->array_to_html_xss($item);
            } else {
                $result[$key] = htmlspecialchars($item);
            }
        }

        return $result;
    }

    //獲取請求文件
    public function files(string $key = '')
    {
        if (empty($key)){
            return $_FILES;
        } else {
            return $_FILES[$key] ?? null;
        }

    }


    //獲取親求頭數據
    public function header(string $key = '')
    {
        if (empty($key)){
            $data = [];
            foreach($_SERVER as $k => $val){
                $http_ = substr($k,0,5);
                if ($http_ === "HTTP_"){
                    $__k = strtolower(substr($k,5,strlen($k)));
                    $data[$__k] = $_SERVER[$k];
                }
            }
            return $data;
        }
        return isset($_SERVER['HTTP_'.strtoupper($key)]) ? $_SERVER['HTTP_'.strtoupper($key)] : null;
    }

}