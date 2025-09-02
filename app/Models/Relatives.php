<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relatives extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'option_value', 'option_name'];

    protected $table = 'user_options';
    // protected $guarded = 'id';

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function selectedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'option_value');
    }
}
