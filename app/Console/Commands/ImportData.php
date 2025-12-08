<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Console\Command;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import WooCommerce product data from CSV';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $csvPath = storage_path('app/product-export.csv');

        if (! file_exists($csvPath)) {
            $this->error('CSV file not found at: '.$csvPath);

            return self::FAILURE;
        }

        $this->info('Importing WooCommerce data...');

        $file = fopen($csvPath, 'r');
        $headers = fgetcsv($file);

        // Remove BOM if present
        if (isset($headers[0])) {
            $headers[0] = str_replace("\xEF\xBB\xBF", '', $headers[0]);
        }

        $headerCount = count($headers);
        $productsCreated = 0;
        $variationsCreated = 0;
        $imagesCreated = 0;
        $parentProducts = [];
        $skippedRows = 0;

        while (($row = fgetcsv($file)) !== false) {
            $rowCount = count($row);

            // Pad row with empty strings if it has fewer columns than headers
            if ($rowCount < $headerCount) {
                $row = array_pad($row, $headerCount, '');
            } elseif ($rowCount > $headerCount) {
                // Skip rows with more columns than headers (likely malformed)
                $skippedRows++;

                continue;
            }

            $data = array_combine($headers, $row);

            $type = $data['Typ'];
            $wcId = $data['ID'];

            if ($type === 'simple') {
                $product = $this->createSimpleProduct($data);
                $productsCreated++;

                $imageCount = $this->createImages($product, $data['Bilder']);
                $imagesCreated += $imageCount;
            } elseif ($type === 'variable') {
                $product = $this->createVariableProduct($data);
                $productsCreated++;
                $parentProducts[$data['Artikelnummer']] = $product;

                $imageCount = $this->createImages($product, $data['Bilder']);
                $imagesCreated += $imageCount;
            } elseif ($type === 'variation') {
                $variation = $this->createVariation($data, $parentProducts);
                $variationsCreated++;

                if ($variation && ! empty($data['Bilder'])) {
                    $imageCount = $this->createImages($variation, $data['Bilder']);
                    $imagesCreated += $imageCount;
                }
            }
        }

        fclose($file);

        $this->info('Import completed!');
        $this->line("Products created: {$productsCreated}");
        $this->line("Variations created: {$variationsCreated}");
        $this->line("Images created: {$imagesCreated}");
        if ($skippedRows > 0) {
            $this->warn("Skipped rows (malformed): {$skippedRows}");
        }

        return self::SUCCESS;
    }

    private function createSimpleProduct(array $data): Product
    {
        $attributes = [];
        for ($i = 1; $i <= 5; $i++) {
            $attrName = $data["Attribut {$i} Name"] ?? null;
            $attrValue = $data["Attribut {$i} Wert(e)"] ?? null;

            if ($attrName && $attrValue) {
                $attributes[$attrName] = [
                    'values' => $attrValue,
                    'visible' => $data["Attribut {$i} Sichtbar"] ?? '0',
                    'global' => $data["Attribut {$i} Global"] ?? '0',
                ];
            }
        }

        return Product::create([
            'wc_id' => $data['ID'],
            'type' => 'simple',
            'sku' => $data['Artikelnummer'] ?: null,
            'name' => $this->cleanName($data['Name']),
            'description' => $this->cleanDescription($data['Kurzbeschreibung'] ?: $data['Beschreibung'] ?: null),
            'published' => $data['Veröffentlicht'] == '1',
            'featured' => $data['Ist hervorgehoben?'] == '1',
            'visibility' => $data['Sichtbarkeit im Katalog'] ?: 'visible',
            'price' => $data['Regulärer Preis'] ?: null,
            'in_stock' => $data['Vorrätig?'] == '1',
            'stock' => $data['Bestand'] ?: null,
            'weight' => $data['Gewicht (kg)'] ?: null,
            'categories' => $data['Kategorien'] ?: null,
            'tags' => $data['Schlagwörter'] ?: null,
            'shipping_class' => $data['Versandklasse'] ?: null,
            'url' => $data['Externe URL'] ?: null,
            'attributes' => ! empty($attributes) ? $attributes : null,
        ]);
    }

    private function createVariableProduct(array $data): Product
    {
        $attributes = [];
        for ($i = 1; $i <= 5; $i++) {
            $attrName = $data["Attribut {$i} Name"] ?? null;
            $attrValue = $data["Attribut {$i} Wert(e)"] ?? null;

            if ($attrName && $attrValue) {
                $attributes[$attrName] = [
                    'values' => $attrValue,
                    'visible' => $data["Attribut {$i} Sichtbar"] ?? '0',
                    'global' => $data["Attribut {$i} Global"] ?? '0',
                    'default' => $data["Attribut {$i} Standard"] ?? null,
                ];
            }
        }

        return Product::create([
            'wc_id' => $data['ID'],
            'type' => 'variable',
            'sku' => $data['Artikelnummer'] ?: null,
            'name' => $this->cleanName($data['Name']),
            'description' => $this->cleanDescription($data['Kurzbeschreibung'] ?: $data['Beschreibung'] ?: null),
            'published' => $data['Veröffentlicht'] == '1',
            'featured' => $data['Ist hervorgehoben?'] == '1',
            'visibility' => $data['Sichtbarkeit im Katalog'] ?: 'visible',
            'price' => null,
            'in_stock' => $data['Vorrätig?'] == '1',
            'stock' => null,
            'weight' => $data['Gewicht (kg)'] ?: null,
            'categories' => $data['Kategorien'] ?: null,
            'tags' => $data['Schlagwörter'] ?: null,
            'shipping_class' => $data['Versandklasse'] ?: null,
            'url' => $data['Externe URL'] ?: null,
            'attributes' => ! empty($attributes) ? $attributes : null,
        ]);
    }

    private function createVariation(array $data, array $parentProducts): ?ProductVariation
    {
        $parentSku = $data['Übergeordnetes Produkt'] ?? null;

        if (! $parentSku || ! isset($parentProducts[$parentSku])) {
            $this->warn("Parent product not found for variation: {$data['Name']} (Parent SKU: {$parentSku})");

            return null;
        }

        $parentProduct = $parentProducts[$parentSku];

        $attributeValues = [];
        for ($i = 1; $i <= 5; $i++) {
            $attrName = $data["Attribut {$i} Name"] ?? null;
            $attrValue = $data["Attribut {$i} Wert(e)"] ?? null;

            if ($attrName && $attrValue) {
                $attributeValues[$attrName] = $attrValue;
            }
        }

        return ProductVariation::create([
            'product_id' => $parentProduct->id,
            'wc_id' => $data['ID'],
            'parent_sku' => $parentSku,
            'sku' => $data['Artikelnummer'] ?: null,
            'name' => $this->cleanName($data['Name']),
            'description' => $data['Beschreibung'] ?: null,
            'published' => $data['Veröffentlicht'] == '1',
            'visibility' => $data['Sichtbarkeit im Katalog'] ?: 'visible',
            'price' => $data['Regulärer Preis'] ?: null,
            'in_stock' => $data['Vorrätig?'] == '1',
            'stock' => $data['Bestand'] ?: null,
            'weight' => $data['Gewicht (kg)'] ?: null,
            'attribute_values' => ! empty($attributeValues) ? $attributeValues : null,
            'image' => $data['Bilder'] ?: null,
        ]);
    }

    private function createImages($model, ?string $imageUrls): int
    {
        if (empty($imageUrls)) {
            return 0;
        }

        $urls = array_map('trim', explode(',', $imageUrls));
        $count = 0;

        foreach ($urls as $position => $url) {
            if (! empty($url)) {
                ProductImage::create([
                    'imageable_id' => $model->id,
                    'imageable_type' => get_class($model),
                    'url' => $url,
                    'position' => $position,
                ]);
                $count++;
            }
        }

        return $count;
    }

    private function cleanName(string $name): string
    {
        // Remove <br>, <br/>, <br /> tags
        $name = preg_replace('/<br\s*\/?>/i', ' ', $name);

        // Replace multiple spaces with single space
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    private function cleanDescription(?string $description): ?string
    {
        if (empty($description)) {
            return null;
        }

        // Replace literal \n with actual newlines
        $description = str_replace('\n', "\n", $description);

        // Replace <br>, <br/>, <br /> with newlines
        $description = preg_replace('/<br\s*\/?>/i', "\n", $description);

        // Replace closing block tags with newlines (div, p, etc.)
        $description = preg_replace('/<\/(div|p|li|tr)>/i', "\n", $description);

        // Replace <strong> and <b> tags - keep content
        $description = preg_replace('/<\/?(?:strong|b)>/i', '', $description);

        // Remove all remaining HTML tags
        $description = strip_tags($description);

        // Decode HTML entities
        $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace multiple consecutive newlines with double newline
        $description = preg_replace('/\n{3,}/', "\n\n", $description);

        // Replace multiple spaces (but not newlines) with single space
        $description = preg_replace('/[^\S\n]+/', ' ', $description);

        // Trim each line
        $lines = explode("\n", $description);
        $lines = array_map('trim', $lines);
        $description = implode("\n", $lines);

        // Remove empty lines at start and end
        $description = trim($description);

        return $description ?: null;
    }
}
