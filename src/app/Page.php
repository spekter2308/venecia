<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'page_ru',
        'page',
        'name',
        'seo_keywords',
        'seo_title',
        'seo_description',
        'seo_keywords_ru',
        'seo_title_ru',
        'seo_description_ru'
    ];
}
