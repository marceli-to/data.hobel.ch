<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wc_id',
        'type',
        'sku',
        'name',
        'description',
        'published',
        'featured',
        'visibility',
        'price',
        'delivery_time',
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
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(ProductImage::class, 'imageable');
    }

    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function productTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function remarks(): HasMany
    {
        return $this->hasMany(Remark::class);
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('position');
    }
}
