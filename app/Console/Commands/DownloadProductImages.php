<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadProductImages extends Command
{
    protected $signature = 'app:download-product-images';

    protected $description = 'Download product images from URLs and store them locally';

    public function handle(): int
    {
        $images = ProductImage::whereNull('name')->get();

        if ($images->isEmpty()) {
            $this->info('No images to download.');

            return self::SUCCESS;
        }

        $this->info("Found {$images->count()} images to download.");

        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($images as $image) {
            try {
                $filename = $this->downloadAndSaveImage($image->url);

                if ($filename) {
                    $image->update(['name' => $filename]);
                    $successCount++;
                } else {
                    $failCount++;
                    $this->newLine();
                    $this->error("Failed to download: {$image->url}");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->newLine();
                $this->error("Error downloading {$image->url}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Downloaded: {$successCount} | Failed: {$failCount}");

        return self::SUCCESS;
    }

    private function downloadAndSaveImage(string $url): ?string
    {
        $response = Http::timeout(30)->get($url);

        if (! $response->successful()) {
            return null;
        }

        $originalFilename = basename(parse_url($url, PHP_URL_PATH));
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION) ?: 'jpg';
        $nameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);

        $safeName = Str::slug($nameWithoutExtension);
        $filename = $safeName.'.'.$extension;

        $counter = 1;
        while (Storage::disk('public')->exists('uploads/'.$filename)) {
            $filename = $safeName.'-'.$counter.'.'.$extension;
            $counter++;
        }

        Storage::disk('public')->put('uploads/'.$filename, $response->body());

        return $filename;
    }
}
