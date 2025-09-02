<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
	
	public function products(){
		return $this->belongsToMany(Product::class,"installment_product");
	}

    public function getPrepayment($price)
    {
        return ceil((($price / 100) * $this->prepayment_percentage));
    }

    public function getFee($price)
    {
		$p=($price - $this->getPrepayment($price)) / $this->installments_count;
        return ceil((($p / 100) * $this->fee_percentage) * $this->period);
    }

    public function getFeeTotal($price)
    {
        return ceil($this->getFee($price) * $this->installments_count);
    }

    public function getInstallment($price, $fee = false)
    {
        $result = ($price - $this->getPrepayment($price)) / $this->installments_count;
        if ($fee) $result = $result + $this->getFee($price);
        return ceil($result);
    }
	
	public function scopeActive($q)
    {
        $q->where('is_active',true);
    }
}
