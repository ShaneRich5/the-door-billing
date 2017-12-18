<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status', 'type', 'note', 'total'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function billing()
    {
        return $this->belongsTo('App\Models\Address', 'id', 'billing_id');
    }

    public function menuItems()
    {
        return $this->belongsToMany('App\Models\MenuItem');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\Delivery');
    }
}
