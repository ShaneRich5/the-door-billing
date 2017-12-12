<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'owner', 'name', 'address_id'
    ];

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }
}
