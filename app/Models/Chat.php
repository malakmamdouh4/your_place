<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'message' ,'date' , 'seen', 'user_id'  , 'receiver_id'
    ];

    protected $hidden = [
        'updated_at' , 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }


}
