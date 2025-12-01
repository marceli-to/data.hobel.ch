<?php

use App\Services\ScraperService;
use Illuminate\Support\Facades\Http;

it('can scrape a URL and extract content using CSS selectors', function () {
    $html = <<<'HTML'
        <html>
            <body>
                <h1 class="product_title">Test Product</h1>
                <span class="sku">12345</span>
                <span class="price">$99.99</span>
            </body>
        </html>
        HTML;

    Http::fake([
        'https://example.com/product' => Http::response($html, 200),
    ]);

    $scraper = new ScraperService;
    $data = $scraper->scrape('https://example.com/product', [
        'title' => 'h1.product_title',
        'article_no' => 'span.sku',
        'price' => 'span.price',
    ]);

    expect($data)
        ->toHaveKey('url', 'https://example.com/product')
        ->toHaveKey('title', 'Test Product')
        ->toHaveKey('article_no', '12345')
        ->toHaveKey('price', '$99.99');
});

it('returns null for missing selectors', function () {
    $html = '<html><body><h1>Title</h1></body></html>';

    Http::fake([
        'https://example.com' => Http::response($html, 200),
    ]);

    $scraper = new ScraperService;
    $data = $scraper->scrape('https://example.com', [
        'missing' => 'div.does-not-exist',
    ]);

    expect($data['missing'])->toBeNull();
});

it('can extract image src attributes', function () {
    $html = '<html><body><img src="https://example.com/image.jpg" /></body></html>';

    Http::fake([
        'https://example.com' => Http::response($html, 200),
    ]);

    $scraper = new ScraperService;
    $data = $scraper->scrape('https://example.com', [
        'image' => 'img',
    ]);

    expect($data['image'])->toBe('https://example.com/image.jpg');
});

it('can save scraped data to JSON file', function () {
    $scraper = new ScraperService;
    $data = [
        'url' => 'https://example.com',
        'title' => 'Test',
    ];

    $filepath = $scraper->saveToJson($data, 'test_scrape');

    expect($filepath)->toBeFile();
    expect(file_get_contents($filepath))->toContain('"title": "Test"');

    unlink($filepath);
});
