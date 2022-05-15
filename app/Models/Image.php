<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path','post_id'
    ];

    protected $hidden = [
        'post' , 'created_at' ,'updated_at'  //  hidden to get method 'ShowController@getArea'
    ];


    public function post()
    {
        return $this->belongsTo('App\Models\Post','post_id');
    }

}
