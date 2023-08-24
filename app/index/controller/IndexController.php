<?php

namespace app\index\controller;


use core\controller\Controller;


class IndexController extends Controller
{

    public function index()
    {

        $data = [
            'network'        => true,
            'prompt'         => "你叫什麽名字",
            'stream'         => false,
            'system'         => "", 'userId' => "#/chat/1692671591211",
            'withoutContext' => false,
        ];

        $ch = curl_init('https://api.binjie.fun/api/generateStream?refer__1360=n4AxRii%3DitKeTRDBqDwnxjEExBjY%3DDuiYD');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch,CURLOPT_REFERER,'https://c.binjie.fun/');
        curl_setopt($ch, CURLOPT_HEADER, true);
        //禁止https协议验证域名，0就是禁止验证域名且兼容php5.6
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        //禁止https协议验证ssl安全认证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        echo "<pre>";
        print_r(curl_getinfo($ch));
        var_dump($response);

    }

    public function add(){}

    public function edit(){}

    public function update(){}

    public function delete(){}

    public function get(){}

}

