<?php

namespace app\api\controller;

use app\lib\ApiResponse;
use app\model\Article;
use core\controller\Controller;
use core\FormRule\FormRule;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $page   = request()->get('page') ?? 1;
        $limit  = request()->get('limit') ?? 10;
        $offset = ($page - 1) * $limit;

        $data = (new Article)->limit("{$offset},{$limit}")->findAll();
        $this->success([
            'data'  => $data,
            'page'  => $page,
            'total' => (new Article)->count(),
        ]);
    }

    public function add()
    {
        $data = request()->post();
        $rule = new FormRule();
        $res  = $rule->validate(
            $data, ['title' => 'required', 'content' => 'required']
        );
        if ( ! $res) {
            $this->error(msg:$rule->errorMessage);
        }
        $id = (new Article)->insert([
            'title'   => $data['title'],
            'content' => $data['content'],
        ]);
        if ( ! $id) {
            $this->error('添加失败');
        }
        $this->success();
    }

}

