<?php

namespace Database\Seeders;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $grocery = Categories::where('name', 'Grocery')->first();
        $dairy = Categories::where('name', 'Dairy')->first();

        $data = [
            [
                'name' => 'Rice',
                'sku' => 'SKU001',
                'image' => 'default.png',
                'barcode' => '1234567890123',
                'unit' => 'kg',
                'price' => 50.00,
                'stock' => 100,
                'vat_parcent' => 5,
                'is_active' => true,
                'category_id' => $grocery->id ?? null
            ],
            [
                'name' => 'Milk',
                'image' => 'default.png',
                'sku' => 'SKU002',
                'barcode' => '1234567890124',
                'unit' => 'litre',
                'price' => 30.00,
                'stock' => 200,
                'vat_parcent' => 5,
                'is_active' => true,
                'category_id' => $dairy->id ?? null
            ],
            [
                'name' => 'Bread',
                'image' => 'default.png',
                'sku' => 'SKU003',
                'barcode' => null,
                'unit' => 'piece',
                'price' => 20.00,
                'stock' => 150,
                'vat_parcent' => 5,
                'is_active' => true,
                'category_id' => $grocery->id ?? null
            ],

        ];
        foreach ($data as $item) {
           if(($item['barcode'] ?? null) === '')
              {
                $item['barcode'] = null;
              }
                Product::updateOrCreate(
                 ['sku' => $item['sku']],
                 $item
                );
            }
        //
    }
}
