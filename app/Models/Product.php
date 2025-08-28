<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'sku',
        'barcode',
        'unit',
        'price',
        'stock',
        'vat_parcent',
        'is_active'  
    ];
    public function setBarcodeAttribute($value)
    {
        $this->attributes['barcode'] = ($value  === '' ? null : $value );
    }
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
    
       
}
