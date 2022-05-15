<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
      'name' , 'post_id'
    ];

    protected $hidden = [
      'created_at' , 'updated_at'
    ];


    public function post()
    {
        return $this->belongsTo('App\Models\Post','post_id');
    }

}
