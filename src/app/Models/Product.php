<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // Ensure that the Product class exists in this namespace


class Product extends Model
{

    protected $fillable = ['name', 'price', 'description', 'status', 'category_id' , 'amount']; // Agrega los atributos que deseas permitir para la asignación masiva

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

