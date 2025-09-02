<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPost extends Model
{
    use HasFactory;

    protected $fillable =['user_id' , 'description'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id', 'id');
    }

    public function postComments()
    {
        return $this->hasMany(PostComment::class , 'post_id', 'id');
    }

}
