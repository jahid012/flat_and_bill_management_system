<?php

namespace Database\Seeders;

use App\Models\HouseOwner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HouseOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houseOwners = [
            [
                'name' => 'মোহাম্মদ আব্দুল করিম',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '+880-1711-123456',
                'address' => '১৫/এ, ধানমন্ডি আর/এ, ঢাকা-১২০৫',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'ফাতেমা খানম',
                'email' => 'fatema.khanom@gmail.com',
                'password' => Hash::make('password123'),
                'phone' => '+880-1712-234567',
                'address' => '২৮/বি, গুলশান-২, ঢাকা-১২১২',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'মো. রফিকুল ইসলাম',
                'email' => 'rafiqul.islam@gmail.com',
                'password' => Hash::make('password123'),
                'phone' => '+880-1713-345678',
                'address' => '৪২/সি, বনানী, ঢাকা-১২১৩',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'রোকেয়া বেগম',
                'email' => 'rokeya.begum@gmail.com',
                'password' => Hash::make('password123'),
                'phone' => '+880-1714-456789',
                'address' => '৭৮/ডি, উত্তরা, সেক্টর-৭, ঢাকা-১২৩০',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'আহমেদুল হাসান',
                'email' => 'ahmedul.hasan@gmail.com',
                'password' => Hash::make('password123'),
                'phone' => '+880-1715-567890',
                'address' => '৩৫/এফ, বারিধারা ডিওএইচএস, ঢাকা-১২২৯',
                'email_verified_at' => now(),
                'is_active' => false, 
            ]
        ];

        foreach ($houseOwners as $owner) {
            HouseOwner::create($owner);
        }
    }
}
