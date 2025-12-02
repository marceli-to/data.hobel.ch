<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WcProduct extends Model
{
    protected $fillable = [
        'wc_id',
        'type',
        'sku',
        'name',
        'short_description',
        'description',
        'published',
        'featured',
        'visibility',
        'price',
        'in_stock',
        'stock',
        'weight',
        'categories',
        'tags',
        'shipping_class',
        'url',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'featured' => 'boolean',
            'in_stock' => 'boolean',
            'price' => 'decimal:2',
            'weight' => 'decimal:2',
            'attributes' => 'array',
        ];
    }

    public function variations(): HasMany
    {
        return $this->hasMany(WcProductVariation::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(WcProductImage::class, 'imageable');
    }
}
