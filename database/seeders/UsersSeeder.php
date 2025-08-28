<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //
        User::updateOrCreate([
            'name' => 'Admin User',
            'email' => ' admin@gmail.com',
            'phone' => '01700000000',
            'user_type' => 'admin',
            'password' => Hash::make('admin12345')]);


        User::updateOrCreate([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'phone' => '01711111111',
            'user_type' => 'manager',
            'password' => Hash::make('manager12345')]);


       User::updateOrCreate([
        'name' => 'Cashier',
        'email' => 'cashier@gmail.com',
        'phone' => '01722222222',   
        'user_type' => 'cashier',
        'password' => Hash::make('cashier12345')]);    

    }



    
}
