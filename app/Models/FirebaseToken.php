<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    protected $fillable = ['device_identifier', 'device_token', 'device_type'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
