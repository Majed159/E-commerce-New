<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Setting extends Model
{
    //mass assignement
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    //  public function casts()
    // {
    //     return [
    //         'rating' => 'integer',
    //         'is_verified_purchase'=>'boolean',
    //         'is_approved'=>'boolean',
    //     ];
    // }


#[Scope()]
    protected function group(Builder $qurey ,string $group):void
    {
        $qurey->where('group',$group);
    }


    //hellper methods

    public static function get($key,$default = null)
    {
        $setting = static::where('key',$key)->first();

         if (!$setting) {
        return $default;
    }
    return static::castValue($setting->value,$setting->type);
    }

    public static function set($key,$value,$type='string',$group='general')
    {
        return static::updateOrCreate(
            ['key'=>$key],
            [
                'value' => $value,
                'type' => $type,
                'group' =>$group,
            ]
            );
    }

    protected static  function castValue($value,$type)
    {
        return match($type){
            'boolen'=> filter_var($value,FILTER_VALIDATE_BOOLEAN),
            'number'=> is_numeric($value) ? (float) $value : $value,
            'json' =>json_decode($value ,true),
        };
    }
}
