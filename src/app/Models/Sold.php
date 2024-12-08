<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product; 


class Sold extends Model
{
    protected $fillable = ['product_id' , 'sold']; 

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
