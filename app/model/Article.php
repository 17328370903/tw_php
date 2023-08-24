<?php

namespace app\model;

use core\model\Model;

class Article extends Model
{


    public string $table = "article";

    public function category()
    {
         return $this->hasOne(Category::class,'id','category_id');
    }

    public function lang()
    {
        return $this->hasOne(Lang::class,'id','lang_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'article_id','id');
    }
}