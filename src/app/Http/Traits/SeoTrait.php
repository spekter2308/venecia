<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Input;

trait SeoTrait {


    public function metaTags($collection){

        if (is_array($collection) && !$collection->isEmpty() ){
            return false;
        }
        $metaTags = [
            'description' => ( isset($collection->seo_description) && !empty($collection->seo_description) ) ? $collection->seo_description : 'Вам потрібне ☝ шкіряне взуття для жінок і чоловіків та аксесуари? Читайте ✍ відгуки ➥ у нас купили за найкращими цінами $ з Рівного, Львова та Києва з доставкою ✈ по всій Україні, великий асортимент товарів і досвідчений персонал - Інтернет-магазин Venezi',
            'title' => ( isset($collection->seo_title) && !empty($collection->seo_title) ) ? $collection->seo_title : 'Інтернет магазин взуття та аксесуарів - купити шкіряне у Львові та Києві онлайн, ціни в Україні — Venezia',
            'keywords' => ( isset($collection->seo_keywords) && !empty($collection->seo_keywords) ) ? $collection->seo_keywords : '',
            'description_ru' => ( isset($collection->seo_description_ru) && !empty($collection->seo_description_ru) ) ? $collection->seo_description_ru : 'Вам нужно ☝ кожаная обувь для женщин и мужчин и аксессуары? Читайте ✍ отзывы ➥ у нас купили по лучшим ценам $ из Ровно, Львова и Киева с доставкой ✈ по всей Украине, большой ассортимент товаров и опытный персонал — Интернет-магазин Venezia',
            'title_ru' => ( isset($collection->seo_title_ru) && !empty($collection->seo_title_ru) ) ? $collection->seo_title_ru : 'Интернет магазин обуви и аксессуаров - купить кожаную онлайн в Украине,цены в каталоге Киева — Venezia',
            'keywords_ru' => ( isset($collection->seo_keywords_ru) && !empty($collection->seo_keywords_ru) ) ? $collection->seo_keywords_ru : ''
        ];
        return $metaTags;
    }


}