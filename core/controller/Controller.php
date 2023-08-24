<?php

namespace core\controller;

use core\tpl\tpl;

class Controller
{
    protected array $assignData = [];

    protected function assign(string $key,mixed $data): void
    {
        $this->assignData[$key] = $data;
    }

    protected function view(string $route = '',array $data = []): void
    {
        $tpl = new tpl($route,[...$this->assignData,...$data]);
        $tpl->tpl_analysis();
    }
}