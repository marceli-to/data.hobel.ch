<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportScrapedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-scraped-data {--fresh : Clear existing data before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import scraped product data from JSON files into the database';

    /**
     * Clean price string by removing CHF and thousand separators
     */
    private function cleanPrice(string $price): string
    {
        // Remove CHF prefix and trim whitespace
        $price = str_replace('CHF', '', $price);
        $price = trim($price);

        // Remove thousand separators (apostrophe or space)
        $price = str_replace(["'", ' '], '', $price);

        return $price;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->info('Clearing existing data...');
            Image::query()->delete();
            Product::query()->delete();
            Category::query()->delete();
        }

        $scrapedPath = storage_path('app/scraped');

        if (! File::exists($scrapedPath)) {
            $this->error('Scraped directory does not exist at: '.$scrapedPath);

            return self::FAILURE;
        }

        $directories = File::directories($scrapedPath);

        if (empty($directories)) {
            $this->error('No category directories found in: '.$scrapedPath);

            return self::FAILURE;
        }

        $this->info('Found '.count($directories).' categories');

        $totalProducts = 0;
        $totalImages = 0;

        foreach ($directories as $directory) {
            $categoryName = basename($directory);

            $category = Category::firstOrCreate(['name' => $categoryName]);

            $this->line("Processing category: {$categoryName}");

            $jsonFiles = File::glob($directory.'/*.json');

            foreach ($jsonFiles as $jsonFile) {
                $data = json_decode(File::get($jsonFile), true);

                if (! $data) {
                    $this->warn('  ⚠ Failed to parse: '.basename($jsonFile));

                    continue;
                }

                // Handle price - check if it's an array
                $price = null;
                $priceRange = null;

                if (isset($data['price'])) {
                    if (is_array($data['price'])) {
                        // Clean and format price range as comma-separated values
                        $cleanedPrices = array_map(fn ($p) => $this->cleanPrice($p), $data['price']);
                        $priceRange = implode(', ', $cleanedPrices);
                    } else {
                        $price = $this->cleanPrice($data['price']);
                    }
                }

                $product = Product::updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'category_id' => $category->id,
                        'url' => $data['url'],
                        'title' => $data['title'],
                        'description' => $data['description'] ?? null,
                        'article_no' => $data['article_no'] ?? null,
                        'price' => $price,
                        'price_range' => $priceRange,
                        'variations' => $data['variations'] ?? null,
                        'scraped_at' => $data['scraped_at'] ?? null,
                    ]
                );

                $totalProducts++;

                // Handle images - can be string or array
                if (isset($data['image'])) {
                    // Delete existing images for this product
                    $product->images()->delete();

                    $images = is_array($data['image']) ? $data['image'] : [$data['image']];

                    foreach ($images as $imageUrl) {
                        Image::create([
                            'product_id' => $product->id,
                            'url' => $imageUrl,
                        ]);
                        $totalImages++;
                    }
                }

                $this->line("  ✓ {$data['title']}");
            }
        }

        $this->newLine();
        $this->info('Import completed successfully!');
        $this->info('Categories: '.Category::count());
        $this->info("Products: {$totalProducts}");
        $this->info("Images: {$totalImages}");

        return self::SUCCESS;
    }
}
