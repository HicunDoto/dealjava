<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelians';
    protected $fillable = [
        'grup_product_id',
        'customer_id'
    ];
    public function group_product(){
        return $this->belongsTo(group_product::class, 'grup_product_id', 'id');
    }
}
