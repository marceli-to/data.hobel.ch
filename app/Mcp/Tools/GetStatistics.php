<?php

namespace App\Mcp\Tools;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductVariation;
use Illuminate\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GetStatistics extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Get statistics about the product database. Returns counts, averages, and breakdowns by category/tag.
        No parameters required - returns a comprehensive overview of the database.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $stats = [
            'totals' => [
                'products' => Product::count(),
                'variations' => ProductVariation::count(),
                'categories' => Category::count(),
                'tags' => Tag::count(),
            ],
            'products' => [
                'in_stock' => Product::where('in_stock', true)->count(),
                'out_of_stock' => Product::where('in_stock', false)->count(),
                'featured' => Product::where('featured', true)->count(),
                'average_price' => round(Product::avg('price') ?? 0, 2),
                'min_price' => Product::min('price'),
                'max_price' => Product::max('price'),
                'total_stock_value' => round(Product::sum(DB::raw('price * stock')) ?? 0, 2),
            ],
            'by_category' => Category::withCount('products')
                ->get()
                ->map(fn($c) => ['name' => $c->name, 'product_count' => $c->products_count])
                ->toArray(),
            'by_tag' => Tag::withCount('products')
                ->get()
                ->map(fn($t) => ['name' => $t->name, 'product_count' => $t->products_count])
                ->toArray(),
        ];

        return Response::text(json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
