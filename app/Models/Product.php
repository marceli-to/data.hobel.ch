<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'url',
        'title',
        'description',
        'article_no',
        'price',
        'price_range',
        'variations',
        'slug',
        'scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'variations' => 'array',
            'scraped_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
