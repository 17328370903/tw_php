<?php

namespace core\curl;

class HttpRequest
{
    public function get(string $url, array $header = [])
    {
        $ch = curl_init($url);
        //设置header头
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        //禁止https协议验证域名，0就是禁止验证域名且兼容php5.6
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        //禁止https协议验证ssl安全认证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //以字符串形式返回到浏览器当中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}

