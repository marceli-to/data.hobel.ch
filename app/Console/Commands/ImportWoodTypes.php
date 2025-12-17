<?php

namespace App\Console\Commands;

use App\Models\WoodType;
use Illuminate\Console\Command;

class ImportWoodTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-wood-types {--fresh : Delete all existing wood types before importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import wood types with prices (50-60mm) from Preisliste 2024';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('fresh')) {
            WoodType::query()->forceDelete();
            $this->info('Deleted all existing wood types.');
        }

        // Laubholz (Hardwood) - Prices for 50-60mm thickness
        $laubholz = [
            ['name' => 'Ahorn', 'price' => 1750],
            ['name' => 'Apfel', 'price' => 4620],
            ['name' => 'Birnbaum gedämpft', 'price' => 2690],
            ['name' => 'Buche natur', 'price' => 1140],
            ['name' => 'Buche gedämpft', 'price' => 1240],
            ['name' => 'Buche mit Kern', 'price' => 1180],
            ['name' => 'Eiche (3,00 - 4,40 m)', 'price' => 2780],
            ['name' => 'Eiche lang (ab 4,50 m)', 'price' => 2980],
            ['name' => 'Eiche charaktervoll, astreich', 'price' => 2370],
            ['name' => 'Eiche geräuchert', 'price' => 4780],
            ['name' => 'Elsbeere gedämpft', 'price' => 2980],
            ['name' => 'Erle', 'price' => 1350],
            ['name' => 'Esche mit Kern', 'price' => 1350],
            ['name' => 'Esche weiss', 'price' => 1740],
            ['name' => 'Edelkastanie', 'price' => 2350],
            ['name' => 'Kirschbaum (Waldkirsche)', 'price' => 2290],
            ['name' => 'Kirschbaum Amerikanisch (Schwarzkirsche)', 'price' => 2430],
            ['name' => 'Nussbaum gedämpft', 'price' => 4800],
            ['name' => 'Nussbaum Amerikanisch gedämpft (Schwarznuss)', 'price' => 4500],
            ['name' => 'Tulip', 'price' => 1400],
            ['name' => 'Ulme gedämpft', 'price' => 2250],
            ['name' => 'Ulme gedämpft, charaktervoll, astreich', 'price' => 1930],
            ['name' => 'Roteiche', 'price' => 1750],
        ];

        // Nadelholz (Softwood) - Prices for 50-60mm thickness
        $nadelholz = [
            ['name' => 'Arve', 'price' => 2250],
            ['name' => 'Fichte (Rottanne)', 'price' => 1400],
            ['name' => 'Fichte gedämpft', 'price' => 1630],
            ['name' => 'Tanne (Weisstanne)', 'price' => 1350],
            ['name' => 'Lärche', 'price' => 1600],
            ['name' => 'Douglas', 'price' => 1500],
        ];

        $allWoodTypes = array_merge($laubholz, $nadelholz);
        $created = 0;
        $updated = 0;

        foreach ($allWoodTypes as $woodType) {
            $existing = WoodType::withTrashed()->where('name', $woodType['name'])->first();
            
            if ($existing) {
                $existing->restore();
                $existing->update(['price' => $woodType['price']]);
                $updated++;
            } else {
                WoodType::create($woodType);
                $created++;
            }
        }

        $this->info('Wood types import completed!');
        $this->line("Created: {$created}");
        $this->line("Updated: {$updated}");
        $this->line("Total: " . count($allWoodTypes));

        return self::SUCCESS;
    }
}
