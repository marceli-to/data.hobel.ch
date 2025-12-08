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

class QueryProducts extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Query the product database. Supports filtering by:
        - category: Filter by category name
        - tag: Filter by tag name
        - in_stock: Filter by stock status (true/false)
        - min_price / max_price: Filter by price range
        - search: Search in product name and description
        - featured: Filter featured products (true/false)
        - limit: Maximum number of results (default 20)
        
        Returns product data including name, SKU, price, stock status, categories, and tags.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $query = Product::query()->with(['productCategories', 'productTags', 'variations']);

        // Filter by category
        if ($category = $request->input('category')) {
            $query->whereHas('productCategories', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Filter by tag
        if ($tag = $request->input('tag')) {
            $query->whereHas('productTags', function ($q) use ($tag) {
                $q->where('name', 'like', "%{$tag}%");
            });
        }

        // Filter by stock status
        if ($request->has('in_stock')) {
            $query->where('in_stock', $request->boolean('in_stock'));
        }

        // Filter by price range
        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        // Search in name and description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter featured products
        if ($request->has('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        $limit = min($request->input('limit', 20), 100);
        $products = $query->limit($limit)->get();

        $result = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'in_stock' => $product->in_stock,
                'stock' => $product->stock,
                'featured' => $product->featured,
                'categories' => $product->productCategories->pluck('name')->toArray(),
                'tags' => $product->productTags->pluck('name')->toArray(),
                'variations_count' => $product->variations->count(),
                'short_description' => $product->short_description,
            ];
        });

        return Response::text(json_encode([
            'count' => $products->count(),
            'products' => $result,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'category' => $schema->string()->description('Filter by category name'),
            'tag' => $schema->string()->description('Filter by tag name'),
            'in_stock' => $schema->boolean()->description('Filter by stock status'),
            'min_price' => $schema->number()->description('Minimum price'),
            'max_price' => $schema->number()->description('Maximum price'),
            'search' => $schema->string()->description('Search in product name, description, or SKU'),
            'featured' => $schema->boolean()->description('Filter featured products'),
            'limit' => $schema->integer()->description('Maximum number of results (default 20, max 100)'),
        ];
    }
}
