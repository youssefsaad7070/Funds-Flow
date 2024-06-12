<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetPhotoUrlController extends Controller
{
    static $baseUrl = 'https://malamute-optimum-recently.ngrok-free.app' ;
    public static function transform($categories)
    {
        $categories->transform(function ($category) {
            $category->photo = self::$baseUrl . asset($category->photo); // Convert to absolute URL
            return $category;
        });
    }

    public static function transformOne($categories){
        $categories->photo = self::$baseUrl . asset($categories->photo);
        return $categories;
    }

    public static function transformCardPhoto($categories){
        $categories->id_card_photo = self::$baseUrl . asset($categories->id_card_photo);
        return $categories;
    }
}
