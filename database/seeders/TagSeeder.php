<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Tische' => [
                'Esstische',
                'Tischplatten',
                'Tischfüsse',
                'Salontische',
                'Stehpulte',
                'Kleintische',
            ],
            'Sitzmöbel' => [
                'Stühle',
                'Hocker',
                'Bänke',
                'Liegestühle',
            ],
            'Gedeckter Tisch' => [
                'Geschirr',
                'Besteck',
                'Schalen + Schüsseln',
                'Gläser + Tassen',
                'Krüge + Kannen',
                'Vasen',
            ],
            'Schneidebretter' => [
                'Rund',
                'Rechteckig',
                'Mit Griff',
            ],
            'Serviertabletts' => [
                'Tabletts',
                'Böckli',
            ],
            'Wohnen' => [
                'Accessoires',
                'Bilderrahmen',
                'Regale',
                'Spiegel',
                'Stabkerzen',
                'Untersetzer',
            ],
        ];

        foreach ($tags as $categoryName => $tagNames) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                foreach ($tagNames as $tagName) {
                    Tag::create([
                        'category_id' => $category->id,
                        'name' => $tagName,
                        'slug' => Str::slug($tagName),
                    ]);
                }
            }
        }
    }
}
