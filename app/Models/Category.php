<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'mobile_cover_image',
        'parent_id',
        'show_on_header',
        'status',
        'sort_order'
    ];

    public function products()
    {
        return Product::whereJsonContains('category_ids', $this->id);
    }

    public function getProductsAttribute()
    {
        return Product::whereJsonContains('category_ids', $this->id)->get();
    }

    public function getProductsCountAttribute()
    {
        return Product::whereJsonContains('category_ids', $this->id)->count();
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all descendant categories.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if category is a root category (has no parent).
     */
    public function isRoot()
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if category has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Generate a unique slug for the category.
     */
    public static function generateUniqueSlug($name, $excludeId = null)
    {
        $baseSlug = \Illuminate\Support\Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
