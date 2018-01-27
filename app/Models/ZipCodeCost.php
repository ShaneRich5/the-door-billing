<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZipCodeCost extends Model
{
    protected $fillable = ['zip_code', 'cost'];


    public function getCostAttribute($value)
    {
        return $value / 100;
    }

    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = ceil($value * 100);
    }
}
