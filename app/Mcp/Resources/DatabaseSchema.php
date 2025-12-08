<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Resource;

class DatabaseSchema extends Resource
{
    /**
     * The resource's description.
     */
    protected string $description = <<<'MARKDOWN'
        Database schema information for the product catalog. Describes all tables, columns, and relationships.
    MARKDOWN;

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $schema = [
            'tables' => [
                'products' => [
                    'description' => 'Main product table',
                    'columns' => [
                        'id' => 'Primary key',
                        'wc_id' => 'WooCommerce ID (external reference)',
                        'type' => 'Product type (simple, variable, etc.)',
                        'sku' => 'Stock Keeping Unit',
                        'name' => 'Product name',
                        'short_description' => 'Brief product description',
                        'description' => 'Full product description',
                        'published' => 'Whether product is published (boolean)',
                        'featured' => 'Whether product is featured (boolean)',
                        'visibility' => 'Product visibility setting',
                        'price' => 'Product price (decimal)',
                        'in_stock' => 'Whether product is in stock (boolean)',
                        'stock' => 'Stock quantity',
                        'weight' => 'Product weight (decimal)',
                        'shipping_class' => 'Shipping class',
                        'url' => 'Product URL',
                        'attributes' => 'Product attributes (JSON)',
                        'done_at' => 'Completion timestamp',
                        'label' => 'Product label',
                    ],
                    'relationships' => [
                        'variations' => 'Has many ProductVariation',
                        'productCategories' => 'Belongs to many Category',
                        'productTags' => 'Belongs to many Tag',
                        'images' => 'Has many ProductImage (polymorphic)',
                        'remarks' => 'Has many Remark',
                    ],
                ],
                'product_variations' => [
                    'description' => 'Product variations (sizes, colors, etc.)',
                    'columns' => [
                        'id' => 'Primary key',
                        'product_id' => 'Foreign key to products',
                        'wc_id' => 'WooCommerce ID',
                        'parent_sku' => 'Parent product SKU',
                        'sku' => 'Variation SKU',
                        'name' => 'Variation name',
                        'label' => 'Variation label',
                        'description' => 'Variation description',
                        'published' => 'Whether variation is published',
                        'visibility' => 'Visibility setting',
                        'price' => 'Variation price',
                        'in_stock' => 'Stock status',
                        'stock' => 'Stock quantity',
                        'weight' => 'Weight',
                        'attribute_values' => 'Attribute values (JSON)',
                        'image' => 'Image reference',
                    ],
                ],
                'categories' => [
                    'description' => 'Product categories',
                    'columns' => [
                        'id' => 'Primary key',
                        'name' => 'Category name',
                        'slug' => 'URL-friendly slug',
                    ],
                ],
                'tags' => [
                    'description' => 'Product tags',
                    'columns' => [
                        'id' => 'Primary key',
                        'name' => 'Tag name',
                        'slug' => 'URL-friendly slug',
                        'category_id' => 'Optional category association',
                    ],
                ],
                'remarks' => [
                    'description' => 'Notes/remarks on products',
                    'columns' => [
                        'id' => 'Primary key',
                        'product_id' => 'Foreign key to products',
                        'user_id' => 'User who created the remark',
                        'content' => 'Remark content',
                    ],
                ],
            ],
        ];

        return Response::text(json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
