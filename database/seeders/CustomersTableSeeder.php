<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        Customer::truncate();
        
        // Create sample data
        $customers = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $customers[] = [
                'Customer_Id' => 1000 + $i,
                'Name' => 'Customer ' . (1000 + $i),
                'Contact' => 'Contact Person ' . $i,
                'Address1' => 'Jl. Contoh No. ' . $i,
                'City' => ['Jakarta', 'Bandung', 'Surabaya'][rand(0,2)],
                'Phone' => '021-' . rand(1000000, 9999999),
                'Mobile_Phone' => '0812' . rand(10000000, 99999999),
                'Credit_Limit' => (string) rand(10000000, 50000000),
                'Term_Id' => rand(1, 3),
                'Birth_Date' => Carbon::now()->subYears(rand(20, 60))->format('Y-m-d'),
                'longtitude' => '106.' . rand(700000, 900000),
                'latitude' => '-6.' . rand(180000, 300000),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Customer::insert($customers);
        
        $this->command->info('✅ 20 customers created successfully!');
    }
}