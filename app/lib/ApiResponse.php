<?php

namespace app\lib;

use JetBrains\PhpStorm\NoReturn;

trait  ApiResponse
{

    #[NoReturn]
    protected  function success(array $data = [],string $msg = "success"): void
    {
        echo json_encode(['code' => 200,'msg' => $msg,'data' => $data],JSON_UNESCAPED_UNICODE);die;
    }

    #[NoReturn]
    protected function error(array $data = [],$msg = "error"): void
    {
        echo json_encode(['code' => 400,'msg' => $msg,'data' => $data],JSON_UNESCAPED_UNICODE);die;
    }


}