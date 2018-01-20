<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'catering_limit'];

    /**
     * The menu items that belong to the tag.
     */
    public function menuItems()
    {
        return $this->belongsToMany('App\Models\MenuItem');
    }
}
