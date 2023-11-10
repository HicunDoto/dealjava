<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class varian_product extends Model
{
    use HasFactory;
    protected $table = 'varian_products';
    protected $fillable = [
        'nama',
        'price',
        'status'
    ];
}
