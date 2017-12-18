<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['attendance', ''];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }
}
