<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(UserPost::class, 'post_id'); // each like belongs to a post
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // each like belongs to a user
    }
}
