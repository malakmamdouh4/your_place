<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id' , 'post_id'
    ];

    protected $hidden = [
      'created_at' , 'updated_at'
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post','post_id');
    }

}
