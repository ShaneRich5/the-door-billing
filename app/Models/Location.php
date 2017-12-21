<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'owner', 'name', 'phone', 'address_id'
    ];

    protected $appends = ['address'];

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function getAddressAttribute()
    {
        return $this->address()->get();
    }
}
