<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['attendance', 'deliver_by', 'cost'];

    protected $appends = ['location'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function getLocationAttribute()
    {
        return $this->location()->first();
    }

    // public function getDeliveryByAttribute($value)
    // {

    // }
}
