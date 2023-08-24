<?php

namespace app\model;

use core\model\Model;

class Category extends Model
{
    public string $table = "category";

    public function lang()
    {
        return $this->hasOne(Lang::class,'id','lang_id');
    }

}