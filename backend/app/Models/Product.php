<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasAdvancedFilters;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasAdvancedFilters;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            $product->slug = $product->id ? $product->id : time();
        });
        
        static::created(function ($product) {
            $product->update(['slug' => $product->id]);
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'is_featured',
        'status',
        'images',
        'gallery',
        'weight',
        'dimensions',
        'dimensions_length',
        'dimensions_width', 
        'dimensions_height',
        'category_id',
        'brand',
        'gender',
        'tags',
        'tags_text',
        'meta_title',
        'meta_description',
        'seo_keywords',
        'seo_keywords_text',
        // Fiziksel özellikler
        'size',
        'color',
        'material',
        'pattern',
        'fit_type',
        'sleeve_type',
        'neckline',
        'shoe_size',
        'heel_height',
        'shoe_type',
        'accessory_type',
        'closure_type',
        'skin_type',
        'shade',
        'volume',
        'expiry_date',
        'is_organic',
        'is_vegan',
        'is_cruelty_free',
        'age_group',
        'season',
        'occasion',
        'care_instructions',
        'ingredients',
        'care_instructions_text',
        'ingredients_text'
    ];

    protected $casts = [
        'images' => 'array',
        'gallery' => 'array',
        'tags' => 'array',
        'seo_keywords' => 'array',
        'dimensions' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'expiry_date' => 'date',
        'is_organic' => 'boolean',
        'is_vegan' => 'boolean',
        'is_cruelty_free' => 'boolean',
        'care_instructions' => 'array',
        'ingredients' => 'array'
    ];

    protected $appends = [
        'final_price',
        'discount_percentage',
        'is_on_sale',
        'image_urls'
    ];

    /**
     * Aranabilir alanlar
     */
    protected $searchable = [
        'name',
        'description',
        'sku',
        'brand',
        'category.name'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }
        
        return collect($this->images)->map(function ($image) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return asset('storage/' . $image);
        })->toArray();
    }
}