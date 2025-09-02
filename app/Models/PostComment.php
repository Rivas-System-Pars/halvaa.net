<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'post_comments';

    public function post()
    {
        return $this->belongsTo(UserPost::class , 'post_id', 'id');
    }

    public function user()

    {
        return $this->belongsTo(User::class);
    }
}
