<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //
        $list = [
            [
                'name' => 'John Doe',
                'email' => 'john@gmail.com',
                'phone' => '01711111111',
                'address' => '123 Main St, Cityville'
            ],
        ];
        foreach ($list as $item) {
            Customer::updateOrCreate([
                'name' => $item['name'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'address' => $item['address'],
            ]);
        }
    }
}
