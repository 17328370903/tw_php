<?php

namespace core\facade;

class Facade
{
    public static function __callStatic($name, $arguments)
    {
        $className = static::getInstance();
        $instance  = new $className;
        return $instance->$name(...$arguments);
    }

}