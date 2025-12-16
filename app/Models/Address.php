<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Address extends Model
{
    protected $fillable = [
        'customer id',
        'full_name',
        'phone',
        'adress_line_1',
        'adress_line_2',
        'is_default',
        'type',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
    ];
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
    #[Scope()]
    protected function default(Builder $builder)  {
        $builder ->where('is_default', true);
    }

    protected function ofType(Builder $builder ,string $type)  {
        $builder->where('is_default',$type);
    }
//relationship
public function customer(){
    return $this->belongsTo(Customer::class);
}
    public function getfullAddressAttribute(): string
    {
        return implode(',',array_filter([
            $this->adress_line_1,
            $this->adress_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])

    );
    }
}
