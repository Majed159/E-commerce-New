<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'data_of_birth',
        'gender',
        'is_active',
    ];

    protected $hhidden =[
        'password',
        'remeber_token',
    ];

    protected function casts():array
    {
        return[
            'email_verified_at' => 'datetime',
            'data_of_birth' => 'date',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    #[Scope]
    protected function active(Builder $query):void
    {
        $query->where('is_active',true);
    }


    
}
