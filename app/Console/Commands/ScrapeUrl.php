<?php

namespace App\Console\Commands;

use App\Services\ScraperService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class ScrapeUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:url {url? : The URL to scrape} {--all : Scrape all URLs from products.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape a URL and extract content using CSS selectors';

    /**
     * Execute the console command.
     */
    public function handle(ScraperService $scraper): int
    {
        if ($this->option('all')) {
            return $this->scrapeAllProducts($scraper);
        }

        $url = $this->argument('url');

        if (! $url) {
            $url = text(
                label: 'Enter the URL to scrape',
                placeholder: 'https://example.com',
                required: true,
                validate: fn (string $value) => filter_var($value, FILTER_VALIDATE_URL) ? null : 'Please enter a valid URL'
            );
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('Please provide a valid URL');

            return self::FAILURE;
        }

        return $this->scrapeSingleUrl($scraper, $url);
    }

    /**
     * Scrape all products from products.json.
     */
    protected function scrapeAllProducts(ScraperService $scraper): int
    {
        $productsFile = storage_path('app/products.json');

        if (! file_exists($productsFile)) {
            $this->error('products.json file not found in storage/app/');

            return self::FAILURE;
        }

        $categories = json_decode(file_get_contents($productsFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON in products.json');

            return self::FAILURE;
        }

        $totalProducts = array_sum(array_map(fn ($cat) => count($cat['products']), $categories));
        $this->info("Found {$totalProducts} products across ".count($categories).' categories');
        $this->newLine();

        $successCount = 0;
        $failureCount = 0;

        foreach ($categories as &$category) {
            $categoryName = $category['category'];
            $this->line("<fg=blue>Category: {$categoryName}</>");

            foreach ($category['products'] as &$product) {
                $url = $product['url'];

                try {
                    $this->line("  Scraping: {$url}");

                    $this->scrapeSingleUrlData($scraper, $url, $categoryName);

                    $product['scraped_at'] = now()->toIso8601String();
                    $successCount++;

                    $this->line('  <fg=green>✓ Success</>');
                } catch (\Exception $e) {
                    $this->line("  <fg=red>✗ Failed: {$e->getMessage()}</>");
                    $failureCount++;
                }
            }

            $this->newLine();
        }

        file_put_contents(
            $productsFile,
            json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $this->info('Scraping completed!');
        $this->line("<fg=green>Success: {$successCount}</>");
        $this->line("<fg=red>Failed: {$failureCount}</>");

        return $failureCount === 0 ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Scrape a single URL and save it.
     */
    protected function scrapeSingleUrl(ScraperService $scraper, string $url, ?string $category = null): int
    {
        $selectors = [
            'title' => 'h1.product_title',
            'description' => 'div.woocommerce-product-details__short-description',
            'article_no' => 'span.sku',
            'price' => 'span.woocommerce-Price-amount.amount bdi',
            'variations' => 'div.variations select',
            'image' => 'div.woocommerce-product-gallery__image img',
        ];

        $this->info('Scraping URL: '.$url);
        $this->newLine();

        try {
            $data = $scraper->scrape($url, $selectors);

            $slug = basename(parse_url($url, PHP_URL_PATH));
            $data['slug'] = $slug;
            $data['scraped_at'] = now()->toIso8601String();

            if ($category) {
                $data['category'] = $category;
            }

            $filename = $data['title'] ? Str::slug($data['title']) : 'scraped_'.now()->format('Y-m-d_H-i-s');

            $directory = $category ? "scraped/{$category}" : 'scraped';
            $filepath = $scraper->saveToJson($data, $filename, $directory);

            $this->info('✓ Successfully scraped the URL');
            $this->newLine();

            $this->line('<fg=gray>Extracted data:</>');
            foreach ($data as $key => $value) {
                if ($key === 'url') {
                    continue;
                }

                $displayValue = is_array($value) ? json_encode($value) : $value;
                $this->line("<fg=cyan>{$key}:</> {$displayValue}");
            }

            $this->newLine();
            $this->info("Data saved to: {$filepath}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to scrape URL: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Scrape a single URL and return the data without displaying output.
     */
    protected function scrapeSingleUrlData(ScraperService $scraper, string $url, string $category): array
    {
        $selectors = [
            'title' => 'h1.product_title',
            'description' => 'div.woocommerce-product-details__short-description',
            'article_no' => 'span.sku',
            'price' => 'span.woocommerce-Price-amount.amount bdi',
            'variations' => 'div.variations select',
            'image' => 'div.woocommerce-product-gallery__image img',
        ];

        $data = $scraper->scrape($url, $selectors);

        $slug = basename(parse_url($url, PHP_URL_PATH));
        $data['slug'] = $slug;
        $data['scraped_at'] = now()->toIso8601String();
        $data['category'] = $category;

        $filename = $data['title'] ? Str::slug($data['title']) : 'scraped_'.now()->format('Y-m-d_H-i-s');

        $scraper->saveToJson($data, $filename, "scraped/{$category}");

        return $data;
    }
}
