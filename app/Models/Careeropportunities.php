<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Careeropportunities extends Model
{
    protected $guarded = ['id'];
	
	protected $casts = ['birth_of_date:date'];
}
