<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingMethods = [
            ['name' => 'Abholung (Schreinerei)', 'price' => null],
            ['name' => 'Abholung (Laden)', 'price' => null],
            ['name' => 'Lieferung (Stadt Zürich)', 'price' => '200'],
            ['name' => 'Lieferung (Schweiz)', 'price' => '400'],
            ['name' => 'Versand (Schweiz)', 'price' => '20'],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::create($method);
        }
    }
}
