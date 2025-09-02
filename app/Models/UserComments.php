<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComments extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'user_comments';

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_user_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
