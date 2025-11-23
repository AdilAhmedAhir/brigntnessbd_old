<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'image',
        'gallery',
        'category_ids',
        'status',
        'featured',
        'weight',
        'attributes',
        'product_meta',
        'product_size'
    ];

    protected $casts = [
        'gallery' => 'array',
        'category_ids' => 'array',
        'attributes' => 'array',
        'product_meta' => 'array',
        'product_size' => 'array',
        'featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2'
    ];

    public function categories()
    {
        if (!$this->category_ids) {
            return collect();
        }
        return Category::whereIn('id', $this->category_ids)->get();
    }

    // Get primary category (first category) - this is not a relationship, just a helper method
    public function getPrimaryCategoryAttribute()
    {
        if (!$this->category_ids || empty($this->category_ids)) {
            return null;
        }
        return Category::find($this->category_ids[0]);
    }

    // If you need a proper relationship to the primary category, use this instead:
    public function primaryCategory(): BelongsTo
    {
        // This assumes you have a primary_category_id field
        // You would need to add this field to your migration and fillable array
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function getCategoryNamesAttribute()
    {
        return $this->categories()->pluck('name')->implode(', ');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isFeatured()
    {
        return $this->featured;
    }

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ? $this->sale_price : $this->price;
    }

    public function hasDiscount()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getSizeImageAttribute()
    {
        return $this->product_meta['size_image'] ?? null;
    }

    public function getSizesStringAttribute()
    {
        if (!$this->product_size || !is_array($this->product_size)) {
            return '';
        }

        return collect($this->product_size)->map(function($size) {
            if (is_array($size) && isset($size['size_name'])) {
                return $size['size_name'];
            }
            return is_string($size) ? $size : '';
        })->filter()->implode(', ');
    }

    /**
     * Get total stock quantity across all sizes
     */
    public function getTotalStockAttribute()
    {
        if (!$this->product_size || !is_array($this->product_size)) {
            return 0;
        }

        return collect($this->product_size)->sum(function($size) {
            return is_array($size) && isset($size['quantity']) ? (int)$size['quantity'] : 0;
        });
    }

    /**
     * Get stock quantity for a specific size
     */
    public function getSizeStock($sizeName)
    {
        if (!$this->product_size || !is_array($this->product_size)) {
            return 0;
        }

        $size = collect($this->product_size)->firstWhere('size_name', $sizeName);
        return $size ? (int)($size['quantity'] ?? 0) : 0;
    }

    /**
     * Check if a specific size is in stock
     */
    public function isSizeInStock($sizeName)
    {
        return $this->getSizeStock($sizeName) > 0;
    }

    /**
     * Get available sizes (with stock > 0)
     */
    public function getAvailableSizesAttribute()
    {
        if (!$this->product_size || !is_array($this->product_size)) {
            return collect();
        }

        return collect($this->product_size)->filter(function($size) {
            return is_array($size) && isset($size['quantity']) && $size['quantity'] > 0;
        });
    }
}
