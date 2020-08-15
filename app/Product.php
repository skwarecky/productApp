<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Product extends Model
{
    protected $table = "product";
    protected $fillable = [
        'name', 'description'
    ];
}
