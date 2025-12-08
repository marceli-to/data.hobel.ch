<?php

namespace Database\Seeders;

use App\Models\WoodType;
use Illuminate\Database\Seeder;

class WoodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $woodTypes = [
            ['name' => 'Ahorn', 'price' => 639.5],
            ['name' => 'Amerikanischer Nussbaum', 'price' => 1378],
            ['name' => 'Birnbaum', 'price' => 1124.5],
            ['name' => 'Buche', 'price' => 519.5],
            ['name' => 'Eiche', 'price' => 1012.5],
            ['name' => 'Esche', 'price' => 604.5],
            ['name' => 'Kirsche', 'price' => 1124.5],
            ['name' => 'Linde', 'price' => 437],
            ['name' => 'Nussbaum', 'price' => 1631.5],
            ['name' => 'Tanne', 'price' => 452],
            ['name' => 'Ulme', 'price' => 765.5],
        ];

        foreach ($woodTypes as $woodType) {
            WoodType::create($woodType);
        }
    }
}
