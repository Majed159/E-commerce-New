<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    use HasFactory;

   protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }


    #[Scope()]
    protected function active(Builder $builder){
        $builder->where('is_active', true);
    }

    #[Scope()]
    protected function sorted(Builder $builder){
        $builder->orderBy('sort_order','asc');
    }
//relationship
    public function products()  {
        return $this->hasMany(Product::class);
    }

     protected static Function boot(){
        parent::boot();

        static::creating(function ($category) {
            if(empty($category->slug)){
                $category->slug= Str::slug($category->name);
            }
        });
        static::updating(function ($category) {
            if($category->isDirty('name') && empty($category->slug)){
                $category->slug= Str::slug($category->name);
            }
        });

        static::saved(function ($category) {
            if ($category->image) {
                $source = storage_path('app/public/' . $category->image);
                $dest = public_path('storage/' . $category->image);
                if (File::exists($source)) {
                    File::ensureDirectoryExists(dirname($dest));
                    File::copy($source, $dest);
                }
            }
        });
    }
}
