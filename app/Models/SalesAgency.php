<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesAgency extends Model
{
    protected $guarded = ['id'];
	
	protected $table = 'sales_agency';

    public function products()
    {
        return $this->belongsToMany(Product::class,'sales_agency_product');
    }
}
