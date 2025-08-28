<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'unit',
        'vat_parcent'   ,
        'vat_amount',
        'line_total'
    ];
}
