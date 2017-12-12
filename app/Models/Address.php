<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street', 'city', 'state', 'postal_code', 'coordinate_id'
    ];

    public function location()
    {
        return $this->hasOne('App\Models\Location');
    }

    public function coordinates()
    {
        return $this->belongsTo('App\Models\Coordinate');
    }
}
