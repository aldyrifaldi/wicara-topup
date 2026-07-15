<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mobile Legends', 'image' => 'categories/ml.png'],
            ['name' => 'Free Fire', 'image' => 'categories/ff.png'],
            ['name' => 'PUBG Mobile', 'image' => 'categories/pubg.png'],
            ['name' => 'Genshin Impact', 'image' => 'categories/genshin.png'],
            ['name' => 'Call of Duty Mobile', 'image' => 'categories/codm.png'],
            ['name' => 'Valorant', 'image' => 'categories/valorant.png'],
            ['name' => 'Steam Wallet', 'image' => 'categories/steam.png'],
            ['name' => 'Google Play', 'image' => 'categories/gplay.png'],
            ['name' => 'Telkomsel', 'image' => 'categories/tsel.png'],
            ['name' => 'XL/Axis', 'image' => 'categories/xl.png'],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'],
                'description' => 'Top up ' . $cat['name'] . ' dengan harga terbaik dan proses instan.',
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }
}