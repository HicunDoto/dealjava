<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group_product extends Model
{
    use HasFactory;
    protected $table = 'group_products';
    protected $fillable = [
        'product_id',
        'variant_id'
    ];
    
    public function variant(){
        return $this->belongsTo(varian_product::class, 'variant_id', 'id');
    }

    public function product(){
        return $this->belongsTo(product::class, 'product_id', 'id');
    }
}
