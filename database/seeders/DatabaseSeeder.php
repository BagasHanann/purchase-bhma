<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'          => 'Admin / Manager',
                'nip'           => '060012001',
                'password'      => bcrypt('12345678'),
                'created_at'    => date("Y-m-d H:i:s"),
				'role'          => 'admin'
            ],
            [
                'name'          => 'Staff Permintaan',
                'nip'           => '060012002',
                'password'      => bcrypt('12345678'),
                'created_at'    => date("Y-m-d H:i:s"),
				'role'          => 'field'
            ],
            [
                'name'          => 'Staff Pembelian',
                'nip'           => '060012003',
                'password'      => bcrypt('12345678'),
                'created_at'    => date("Y-m-d H:i:s"),
				'role'          => 'staff'
            ],
        ]);
    }
}
