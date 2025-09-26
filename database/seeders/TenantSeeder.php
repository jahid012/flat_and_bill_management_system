<?php

namespace Database\Seeders;

use App\Models\Flat;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupiedFlats = Flat::where('is_occupied', true)->get();

        if ($occupiedFlats->isEmpty()) {
            return;
        }

                $firstNames = [
            'মোহাম্মদ', 'ফাতেমা', 'আব্দুল', 'খাদিজা', 'মো. করিম', 'আয়েশা', 'আহমদ', 'রোকেয়া',
            'রফিক', 'সালমা', 'করিম', 'নাসরিন', 'রহিম', 'শাহনাজ', 'হাসান', 'রুবিনা',
            'আলী', 'জামিলা', 'ইউসুফ', 'রেহানা', 'তারিক', 'সুমাইয়া', 'নাসির', 'মরিয়ম',
            'জহির', 'ফরিদা', 'সাকিব', 'নাদিয়া', 'কামাল', 'তাহমিনা', 'শফিক', 'পারভীন',
            'আনোয়ার', 'সাবিনা', 'মনির', 'রাবেয়া', 'আকতার', 'সীমা'
        ];

        $lastNames = [
            'আহমেদ', 'হোসেন', 'রহমান', 'ইসলাম', 'খান', 'আলী', 'হাসান', 'উদ্দিন',
            'বেগম', 'খাতুন', 'আক্তার', 'করিম', 'মোল্লা', 'শেখ', 'মিয়া', 'চৌধুরী',
            'সরকার', 'খন্দকার', 'তালুকদার', 'শাহ', 'পাশা', 'বুলবুল', 'রেজা', 'নবী',
            'গাজী', 'ফকির', 'দেওয়ান', 'বাড়ৈ', 'কাজী', 'মুন্সী', 'পটওয়ারী', 'ডাক্তার',
            'ইঞ্জিনিয়ার', 'প্রফেসর', 'উকিল', 'ব্যবসায়ী', 'কর্মকার', 'সুবেদার'
        ];

        foreach ($occupiedFlats as $flat) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');

            // Some flats might have multiple tenants (family/roommates)
            $numTenants = rand(1, 2); 

            for ($i = 0; $i < $numTenants; $i++) {
                if ($i > 0) {
                    
                    $firstName = $firstNames[array_rand($firstNames)];
                    $fullName = $firstName . ' ' . $lastName;
                    $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');
                }

                $bdAddresses = [
                    'ধানমন্ডি, ঢাকা', 'গুলশান, ঢাকা', 'বনানী, ঢাকা', 'উত্তরা, ঢাকা',
                    'বারিধারা, ঢাকা', 'মিরপুর, ঢাকা', 'মগবাজার, ঢাকা', 'নিউ ইস্কাটন, ঢাকা',
                    'আগ্রাবাদ, চট্টগ্রাম', 'নাসিরাবাদ, চট্টগ্রাম', 'জামালপুর, সিলেট'
                ];

                Tenant::create([
                    'name' => $fullName,
                    'email' => $email,
                    'phone' => '+880-17' . rand(10, 19) . '-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'address' => $bdAddresses[array_rand($bdAddresses)],
                    'building_id' => $flat->building_id,
                    'flat_id' => $flat->id,
                    'lease_start_date' => now()->subMonths(rand(1, 24)),
                    'lease_end_date' => now()->addMonths(rand(6, 36)),
                    'security_deposit' => rand(10000, 50000), 
                    'is_active' => rand(0, 10) > 1, 
                ]);
            }
        }
    }
}
