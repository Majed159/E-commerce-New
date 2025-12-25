<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable =[
'product_id',
        'image_path',
        'alt_text',
        'is_primary',
        'product_variant_id',
        'sort_order',
    ];


        protected function casts():array
        {
            return[
                'is_primary' =>'boolean',
                'sort_order' =>'integer',
            ];
        }
        #[Scope]

        protected function primary(Builder $query):void
        {
            $query->where('is_primary',true);
        }

        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        public function variant()
        {
            return $this->belongsTo(ProductVariant::class,'product_variant_id');
        }

        //helper method
        public function getUrlAttribute()  {
            return asset('storage/' . $this->image_path);
        }

}
