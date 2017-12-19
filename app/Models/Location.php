<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'owner', 'name', 'phone', 'address_id'
    ];

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }
}
