<?php
////打印
//use JetBrains\PhpStorm\NoReturn;
//
//if (!function_exists('dd')){
//    #[NoReturn]
//    function dd(...$params): void
//    {
//        $data = var_export($params,true);
//        $html = <<<HTML
//        <!doctype html>
//        <html lang="en">
//        <head>
//        <meta charset="UTF-8">
//        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
//        <meta http-equiv="X-UA-Compatible" content="ie=edge">
//        <title>打印</title>
//        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
//        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
//        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/go.min.js"></script>
//
//        </head>
//        <body>
//        <div class="code" style="white-space: pre;width: max-content;"></div>
//
//        </body>
//        </html>
//        <script>
//           let box = document.querySelector(".code")
//           box.innerHTML = hljs.highlight(`$data`,{language:"php"}).value;
//
//        </script>
//    HTML;
//        echo $html;
//        die;
//    }
//}
//
//
//if (!function_exists('dump')){
//    //打印
//    function dump(...$params): void
//    {
//        echo "<pre />";
//        var_dump(...$params);
//    }
//}
//
//
//
/**
 * 獲取請求對象
 * @return \core\common\request\Request
 */
function request(): \core\common\request\Request
{
    return new \core\common\request\Request();
}

//跳转
function jump($url){
    header("location:{$url}");exit();
}


// 加密函数
function encrypt($data, $key, $algorithm = 'AES-256-CBC'): string
{
    $ivLength = openssl_cipher_iv_length($algorithm);
    $iv = openssl_random_pseudo_bytes($ivLength); // 生成随机的初始向量

    $encryptedData = openssl_encrypt($data, $algorithm, $key, OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv . $encryptedData);
}

// 解密函数
function decrypt($encryptedData, $key, $algorithm = 'AES-256-CBC'): false|string
{
    $data = base64_decode($encryptedData);
    $ivLength = openssl_cipher_iv_length($algorithm);
    $iv = substr($data, 0, $ivLength); // 获取初始向量部分
    $encryptedData = substr($data, $ivLength); // 获取加密后的数据部分

    $decryptedData = openssl_decrypt($encryptedData, $algorithm, $key, OPENSSL_RAW_DATA, $iv);

    return $decryptedData;
}

