<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class MenuItem extends Model
{
    use Sluggable;

    protected $fillable = ['name', 'slug', 'description', 'image', 'category_id'];

    protected $appends = ['tags'];

    public function getTagsAttribute()
    {
        $tags = $this->tags()->get()->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        });

        return $tags;
    }

    /**
     * The tag that belong to the menu items.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
