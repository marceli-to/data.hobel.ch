<?php

namespace App\Console\Commands;

use App\Models\ProductVariation;
use Illuminate\Console\Command;

class PopulateVariationLabels extends Command
{
    protected $signature = 'variations:populate-labels';

    protected $description = 'Populate variation labels from attribute_values';

    public function handle()
    {
        $variations = ProductVariation::whereNotNull('attribute_values')->get();

        $this->info("Processing {$variations->count()} variations...");

        $bar = $this->output->createProgressBar($variations->count());
        $bar->start();

        foreach ($variations as $variation) {
            $attributeValues = $variation->attribute_values;

            if (is_array($attributeValues) && !empty($attributeValues)) {
                $values = array_map(function ($value) {
                    // Replace escaped comma (\,) with decimal point
                    return str_replace('\,', '.', $value);
                }, array_values($attributeValues));
                $label = implode(', ', $values);
                $variation->update(['label' => $label]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done!');

        return Command::SUCCESS;
    }
}
