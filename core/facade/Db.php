<?php

namespace core\facade;

class Db extends Facade
{

    public static function getInstance(): string
    {
        return \core\db\Db::class;
    }


}