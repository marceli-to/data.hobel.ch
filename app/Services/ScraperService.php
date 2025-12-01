<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScraperService
{
    /**
     * Scrape a URL and extract content based on CSS selectors.
     *
     * @param  array<string, string>  $selectors
     * @return array<string, mixed>
     */
    public function scrape(string $url, array $selectors): array
    {
        $response = Http::timeout(30)->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException("Failed to fetch URL: {$url}. Status: {$response->status()}");
        }

        $crawler = new Crawler($response->body());

        $crawler->filter('section.related.products')->each(function (Crawler $node) {
            $domNode = $node->getNode(0);
            if ($domNode && $domNode->parentNode) {
                $domNode->parentNode->removeChild($domNode);
            }
        });

        $data = ['url' => $url];

        foreach ($selectors as $key => $selector) {
            if ($key === 'variations') {
                $data[$key] = $this->extractVariations($crawler);
            } elseif ($key === 'image') {
                $data[$key] = $this->extractImages($crawler, $selector);
            } else {
                $data[$key] = $this->extractContent($crawler, $selector);
            }
        }

        return $data;
    }

    /**
     * Extract content from the crawler using a CSS selector.
     */
    protected function extractContent(Crawler $crawler, string $selector): mixed
    {
        try {
            $elements = $crawler->filter($selector);

            if ($elements->count() === 0) {
                return null;
            }

            if ($elements->count() === 1) {
                $element = $elements->first();

                if ($element->nodeName() === 'img') {
                    return $element->attr('src');
                }

                if ($element->nodeName() === 'select') {
                    return $this->extractSelectOptions($element);
                }

                return trim($element->text());
            }

            return $elements->each(function (Crawler $node) {
                if ($node->nodeName() === 'img') {
                    return $node->attr('src');
                }

                if ($node->nodeName() === 'select') {
                    return $this->extractSelectOptions($node);
                }

                return trim($node->text());
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract images, preferring the largest size from srcset.
     */
    protected function extractImages(Crawler $crawler, string $selector): mixed
    {
        try {
            $elements = $crawler->filter($selector);

            if ($elements->count() === 0) {
                return null;
            }

            $images = $elements->each(function (Crawler $img) {
                return $this->getLargestImage($img);
            });

            $images = array_filter($images);

            if (empty($images)) {
                return null;
            }

            return count($images) === 1 ? $images[0] : array_values($images);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the largest image URL from an img element's srcset or src.
     */
    protected function getLargestImage(Crawler $img): ?string
    {
        $srcset = $img->attr('srcset');

        if ($srcset) {
            $sources = array_map('trim', explode(',', $srcset));
            $largestWidth = 0;
            $largestUrl = null;

            foreach ($sources as $source) {
                if (preg_match('/^(.+?)\s+(\d+)w$/', trim($source), $matches)) {
                    $url = $matches[1];
                    $width = (int) $matches[2];

                    if ($width > $largestWidth) {
                        $largestWidth = $width;
                        $largestUrl = $url;
                    }
                }
            }

            if ($largestUrl) {
                return $largestUrl;
            }
        }

        return $img->attr('src');
    }

    /**
     * Extract variations from the product page.
     */
    protected function extractVariations(Crawler $crawler): ?array
    {
        try {
            $variations = [];
            $crawler->filter('div.variations .col-sm-4')->each(function (Crawler $variation) use (&$variations) {
                $label = $variation->filter('label')->count() > 0
                    ? trim($variation->filter('label')->text())
                    : null;

                $select = $variation->filter('select');
                if ($select->count() > 0 && $label) {
                    $options = $this->extractSelectOptions($select->first());
                    if (! empty($options)) {
                        $variations[] = [
                            'label' => $label,
                            'options' => $options,
                        ];
                    }
                }
            });

            return empty($variations) ? null : $variations;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract options from a select element, excluding default prompts.
     */
    protected function extractSelectOptions(Crawler $select): array
    {
        $excludePatterns = [
            'Wählen Sie eine Option',
            'Choose an option',
            'Select an option',
        ];

        $options = $select->filter('option')->each(function (Crawler $option) use ($excludePatterns) {
            $text = trim($option->text());
            $value = $option->attr('value');

            if (empty($value) || in_array($text, $excludePatterns, true)) {
                return null;
            }

            return [
                'value' => $value,
                'label' => $text,
            ];
        });

        return array_values(array_filter($options, fn ($item) => $item !== null));
    }

    /**
     * Save scraped data to a JSON file.
     */
    public function saveToJson(array $data, string $filename, string $subdirectory = 'scraped'): string
    {
        $directory = storage_path('app/'.$subdirectory);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filepath = $directory.'/'.$filename.'.json';

        file_put_contents(
            $filepath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return $filepath;
    }
}
