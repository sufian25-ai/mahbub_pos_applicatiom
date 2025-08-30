<?php

namespace App\Http\Controllers\API;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Sale_item; 
use App\Models\Stock_movement;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['nullable', 'string'],
            'customer_phone' => ['nullable', 'string'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', Rule::in(['cash', 'card', 'mobile_banking'])],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.vat_parcent' => ['nullable', 'numeric', 'min:0'],
            'items.*.unit' => ['nullable', Rule::in(['piece', 'kg', 'gram', 'litre', 'meter'])],
      

        ]);
        $customer = null;
        if(!empty($data['customer_phone'])){
            $customer = Customer::firstOrCreate(
                ['phone' => $data['customer_phone']],
                ['name' => $data['customer_name'] ?? 'Walk-in Customer']
            );
        }
        $discount = $data['discount'] ?? 0;
        return DB::transaction(function() use ($data, $customer, $discount, $request){
            $subtotal = 0;
            $total_vat = 0;
            $itemToInsert = [];
            foreach($data['items'] as $item){
                $product = Product::lockForUpdate()->find($item['product_id']);
                if(!$product) abort(422, "Product ID {$item['product_id']} not found.");
                if($item['unit'] !== $product->unit){
                    abort(422, "Product ID {$item['product_id']} unit mismatch.");
                }
                $qty = (float)$item['quantity'];
                if($qty >= 0) abort(422, "Product ID {$item['product_id']} invalid quantity.");
                if($product->stock < abs($qty)) 
                {
                    abort(422, "Product ID {$item['product_id']} stock is insufficient.");
                }
                $unitPrice = (float)$item['unit_price'];
                $vatPercent = isset($item['vat_parcent']) ? (float)$item['vat_parcent'] : 0;
                $lineAmount = round($unitPrice * abs($qty), 2);
                $lineVat = round($lineAmount * $vatPercent / 100, 2);
                $lineTotal = $lineAmount + $lineVat;
                $subtotal += $lineAmount;
                $total_vat += $lineVat;

                $product->decrement('stock', abs($qty));
                $itemToInsert[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'vat_parcent' => $vatPercent,
                    'line_total' => $lineTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                Stock_movement::create([
                    'product_id' => $product->id,
                    'change_quantity' => $qty,
                    'type' => 'sale',
                    'description' => 'Sale via POS',
                    'created_by' => $request->user()->id,
                ]);

            }
            $calculatedTotal = $subtotal + $total_vat - $discount;
            if(round($calculatedTotal,2) !== round($data['total_amount'],2)){
                abort(422, "Total amount mismatch.");   
            }

            // Create the sale
            $sale = Sale::create([
                'customer_id' => $customer ? $customer->id : null,
                'total_amount' => $data['total_amount'],
                'discount' => $discount,
                'payment_method' => $data['payment_method'],
                'paid_amount' => $data['paid_amount'],
                'subtotal' => $subtotal,
                'total_vat' => $total_vat,
                'created_by' => $request->user()->id,
            ]);

            // Insert sale items
            foreach ($itemToInsert as &$item) {
                $item['sale_id'] = $sale->id;
            }
            Sale_item::insert($itemToInsert);

            return response()->json([
                'message' => 'Sale created successfully',
                'sale_id' => $sale->id,
            ], 201);
        });
    }
}