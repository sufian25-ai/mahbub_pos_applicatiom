<?php

namespace App\Http\Controllers\API;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request)
    {
    	$q = trim((string)$request->query('q',''));
    	$items = Product::query()->where($q !== '', function($qry) use ($q){

    		$qry->where('name', 'like', "%{$q}%")->orWhere('sku','like',"%{$q}%")->orWhere('barcode','like',"%{$q}%")->orWhere('id', intval($q));
    	})->where('is_active', true)->orderBy('name')->limit(20)->get();

    	return response()->json($items);
    }

    public function find(Request $request)
    {
    	$id = $request->query('id');
    	$sku = $request->query('sku');
    	$barcode = $request->query('barcode');

    	$product = Product::when($id, fn($q) => $q->where('id', $id))->when($sku, fn($q) => $q->orWhere('sku', $sku))->when($barcode, fn($q) => $q->orWhere('barcode', $barcode))->first();

    	if(!$product) return response()->json(['message' => 'Not Found'], 404);
    	return response()->json($product);
    }
}
