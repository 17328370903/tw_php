<?php

namespace app\index\controller;

use app\model\User;
use core\controller\Controller;
use core\FormRule\FormRule;

class LoginController extends Controller
{
    public function index()
    {
        $from = new FormRule();
        $data = request()->get();
        $res  = $from->validate($data,
            [
                'name' => 'required',
                'pwd'  => 'required',
            ],
            [
                'name.required' => '請輸入登錄名稱',
                'pwd.required'  => '請輸入登錄密碼',
            ]
        );
        if ( ! $res) {
            echo $from->errorMessage;
            die;
        }


        $user = (new User)->where('name=?', [$data['name']])->find();
        if ( ! $user) {
            echo "用戶不存在";
            die;
        }
        echo "登錄成功";
    }

}