<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product; // Ensure that the Product class exists in this namespace

class Category extends Model
{
    protected $fillable = ['name']; // Agrega los atributos que deseas permitir para la asignación masiva

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}