<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Symfony\Polyfill\Uuid\Uuid;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c1 = Category::create([
            'name' => 'Steel',
            'photo' => 'CategoriesImages\steel.png',
        ]);

        $c2 = Category::create([
            'name' => 'Real Estate',
            'photo' => 'CategoriesImages\realestate.png',
        ]);

        $c3 = Category::create([
            'name' => 'Medical',
            'photo' => 'CategoriesImages\medical.png',
        ]);

        $c4 = Category::create([
            'name' => 'Technology',
            'photo' => 'CategoriesImages\technology.png',
        ]);

        $c5 = Category::create([
            'name' => 'Chemical',
            'photo' => 'CategoriesImages\chemical.png',
        ]);

        $c6 = Category::create([
            'name' => 'Energy',
            'photo' => 'CategoriesImages\energy.png',
        ]);

        
    }
}