<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status', 'type', 'note', 'subtotal', 'tax', 'total', 'invoice'
    ];

    protected $appends = ['billing'];

    public function getTaxAttribute($value)
    {
        return $value / 100;
    }

    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function getSubtotalAttribute($value)
    {
        return $value / 100;
    }

    public function setTaxAttribute($value)
    {
        $this->attributes['tax'] = ceil($value * 100);
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = ceil($value * 100);
    }

    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = ceil($value * 100);
    }


    public function getBillingAttribute()
    {
        return $this->billing()->first();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function billing()
    {
        return $this->belongsTo('App\Models\Address', 'billing_address_id', 'id');
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
