<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'total_vat',
        'discount',
        'total_amount',
        'paid_amount',
        'payment_method',
        'status',
        'user_id'
    ];
    public function saleItems()
    {
        return $this->hasMany(Sale_item::class);
    }

    public static function nextInvoiceNumber(): string
    {
        $prefix = 'INV-'.Now()->format('Ymd').'-';
        $lastSale = self::where('invoice_number', 'like', $prefix.'%')->orderBy('invoice_number', 'desc')->first();
        if (!$lastSale) 
            return $prefix.'0001';
        $n = (int) substr($lastSale->invoice_number, strlen($prefix));
        $n++;
        return $prefix.str_pad($n, 4, '0', STR_PAD_LEFT);   
     
    }
    

}
