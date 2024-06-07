<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetPhotoUrlController extends Controller
{
    public static function transform($categories)
    {
        $categories->transform(function ($category) {
            $category->photo = 'https://malamute-optimum-recently.ngrok-free.app' . asset($category->photo); // Convert to absolute URL
            return $category;
        });
    }

    public static function transformOne($categories){
        $categories->photo = 'https://malamute-optimum-recently.ngrok-free.app' . asset($categories->photo);
        return $categories;
    }

    public static function transformCardPhoto($categories){
        $categories->id_card_photo = 'https://malamute-optimum-recently.ngrok-free.app' . asset($categories->id_card_photo);
        return $categories;
    }
}
