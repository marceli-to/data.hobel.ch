<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'wc_id',
        'parent_sku',
        'sku',
        'name',
        'description',
        'published',
        'visibility',
        'price',
        'in_stock',
        'stock',
        'weight',
        'attribute_values',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'in_stock' => 'boolean',
            'price' => 'decimal:2',
            'weight' => 'decimal:2',
            'attribute_values' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(ProductImage::class, 'imageable');
    }
}
