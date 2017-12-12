<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status', 'type'
    ];

    public function menuItems()
    {
        return $this->belongsToMany('App\Models\MenuItem');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\Delivery');
    }
}
