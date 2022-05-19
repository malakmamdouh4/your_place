<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description','price' , 'phone','avatar' , 'longitude' , 'latitude', 'date' , 'name' , 'avatar' ,
        'category' , 'company' , 'type' , 'bedrooms' , 'bathrooms' , 'area' , 'level' , 'furnished' , 'compound' ,
         'deliveryDate' , 'deliveryTerm', 'user_id' , 'activate'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image','post_id');
    }

    public function amenities()
    {
        return $this->hasMany('App\Models\Amenity','post_id');
    }


    public function saves()
    {
        return $this->hasMany('App\Models\Save','post_id');
    }

    public function shares()
    {
        return $this->hasMany('App\Models\share','post_id');
    }


}
