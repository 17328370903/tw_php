<?php

namespace core\model;

use core\db\Db;

class Model extends Db
{

    public function with(string|array $rel)
    {
        if ( ! $rel) {
            return $this;
        }
        if (is_string($rel)) {
            $relArr = explode(":", $rel);
            $count  = count($relArr);
            if ($count == 1) {
                $relArr = explode(".", $rel);
                $_count = count($relArr);
                if ($_count == 1) {
                    $this->relName = $rel;
                } else {
                    $this->relName = $relArr[0];
                    $tempRelArr    = $relArr;
                    unset($tempRelArr[0]);
                    $this->relWith = implode('.', $tempRelArr);
                }
            } elseif ($count == 2) {
                $this->relName   = $relArr[0];
                $this->relFields = $relArr[1];
            } else {
                throw new \Exception('error');
            }
            $rel = $relArr[0];
            $this->$rel();
        } elseif (is_array($rel)) {
            foreach ($rel as $key => $func) {
                if (is_string($func)) {

                    $relArr = explode(":", $func);
                    $count  = count($relArr);
                    if ($count == 1) {
                        $relArr = explode(".", $func);
                        $_count = count($relArr);
                        if ($_count == 1) {
                            $this->relName = $func;
                        } else {
                            $this->relName = $relArr[0];
                            $tempRelArr    = $relArr;
                            unset($tempRelArr[0]);
                            $this->relWith = implode('.', $tempRelArr);
                        }

                    } elseif ($count == 2) {
                        $this->relName   = $relArr[0];
                        $this->relFields = $relArr[1];
                    } else {
                        throw new \Exception('error');
                    }
                    $func = $relArr[0];
                    $this->$func();
                } elseif (is_array($func)) {
                    $this->relName = $key;
                    $this->relWith = $func;
                    $this->$key();
                } elseif ($func instanceof \Closure) {
                    $this->relClosure = $func;
                    $this->relName    = $key;
                    $this->$key();
                }
            }
        } else {
            throw new \Exception('error');
        }

        return $this;
    }

    //一對一
    protected function hasOne($className, $rel_key, $local_key)
    {

        $this->hasOne[$this->relName] = [
            'className' => $className,
            'rel_key'   => $rel_key,
            'local_key' => $local_key,
            'fields'    => $this->relFields,
            'with'      => $this->relWith,
            'closure'   => $this->relClosure,
        ];
        $this->relFields              = "";
        $this->relName                = '';
        $this->relWith                = [];
        $this->relClosure             = null;

        return $this;
    }

    //一對多
    protected function hasMany($className, $rel_key, $local_key)
    {
        $this->hasMany[$this->relName] = [
            'className' => $className,
            'rel_key'   => $rel_key,
            'local_key' => $local_key,
            'fields'    => $this->relFields,
            'with'      => $this->relWith,
            'closure'   => $this->relClosure,
        ];
        $this->relFields               = "";
        $this->relName                 = '';
        $this->relWith                 = [];
        $this->relClosure              = null;

        return $this;
    }


}