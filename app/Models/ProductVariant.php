<?php

namespace App\Models;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Void_;

class ProductVariant extends Model
{
    protected $fillable =[
        'product_id',
        'name',
        'sku',
        'price',
        'compare_price',
        'options',
        'stock_quantity',

        'stock_status',
        'is_active',
        'sort_order',
    ];

    protected function casts():array
    {
        return [
            'price'=>'decimal:2',
            'oprions'=>'decimal:2',
            'compare_price'=>'decimal:2',
            'stock_quantity'=>'integer',
            'is_active'=>'boolean',
            'weight'=>'decimal:2',
        ];
    }


    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
    #[Scope]

    protected function inStock(Builder $query):void{
        $query->where('stock_status','in_stock')
        ->where('stock_quantity','>','0');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
//helper method
    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round ((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    //event
    protected static function boot():Void
    {
        parent::boot();
        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku ='VAR-'.strtoupper(Str::random(8));
            }
        });
    }
}
