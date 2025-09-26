<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Admin::create([
            'name' => 'সুপার অ্যাডমিন',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        
        $admins = [
            [
                'name' => 'মো. আবুল কালাম',
                'email' => 'abul.kalam@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'সারা বেগম',
                'email' => 'sara.begum@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'মাইকেল রহমান',
                'email' => 'michael.rahman@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_active' => false, 
            ]
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
