<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Seed service status
        DB::table('service_status')->insert([
            ['status_id' => 1, 'status_name' => 'pending'],
            ['status_id' => 2, 'status_name' => 'in progress'],
            ['status_id' => 3, 'status_name' => 'completed'],
        ]);

        // Seed transaction status
        DB::table('transaction_status')->insert([
            ['status_id' => 1, 'status_name' => 'unpaid'],
            ['status_id' => 2, 'status_name' => 'paid'],
            ['status_id' => 3, 'status_name' => 'cancelled'],
        ]);

        // Seed employees
        DB::table('employees')->insert([
            ['employee_id' => 1, 'employee_name' => 'Budi Teknisi'],
            ['employee_id' => 2, 'employee_name' => 'Sari Hardware'],
            ['employee_id' => 3, 'employee_name' => 'Andi Software'],
        ]);

        // Seed locations (office)
        DB::table('locations')->insert([
            ['office_id' => 1, 'office_address' => 'Jl. Service Center No. 123, Jakarta'],
        ]);

        // Seed services dengan harga
        DB::table('services')->insert([
            [
                'service_id' => 1,
                'employee_id' => 2,
                'service_price' => 150000,
                'service_type' => 'Service Hardware',
                'office_id' => 1,
                'status_id' => 3
            ],
            [
                'service_id' => 2,
                'employee_id' => 3,
                'service_price' => 100000,
                'service_type' => 'Service Software',
                'office_id' => 1,
                'status_id' => 3
            ],
            [
                'service_id' => 3,
                'employee_id' => 1,
                'service_price' => 75000,
                'service_type' => 'Maintenance & Cleaning',
                'office_id' => 1,
                'status_id' => 3
            ],
            [
                'service_id' => 4,
                'employee_id' => 2,
                'service_price' => 120000,
                'service_type' => 'Service Printer & PC',
                'office_id' => 1,
                'status_id' => 3
            ],
        ]);

        // Buat admin user
        DB::table('users')->insert([
            'user_id' => 999,
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'phone_number' => '08123456789',
            'full_name' => 'Administrator'
        ]);
    }
}
