<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\HouseOwner;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houseOwners = HouseOwner::where('is_active', true)->get();

        if ($houseOwners->isEmpty()) {
            return;
        }

                $buildings = [
            [
                'house_owner_id' => 1,
                'name' => 'সূর্যোদয় অ্যাপার্টমেন্ট',
                'address' => '১৫/এ, ধানমন্ডি আর/এ',
                'city' => 'ঢাকা',
                'state' => 'ঢাকা বিভাগ',
                'zip_code' => '১২০৫',
                'total_floors' => 5,
                'total_flats' => 20,
                'description' => 'আধুনিক বাসস্থান কমপ্লেক্স, সুন্দর দৃশ্য সহ',
            ],
            [
                'house_owner_id' => 1,
                'name' => 'বাগান ভিউ কমপ্লেক্স',
                'address' => '২৮/বি, গুলশান-২',
                'city' => 'ঢাকা',
                'state' => 'ঢাকা বিভাগ',
                'zip_code' => '১২১২',
                'total_floors' => 3,
                'total_flats' => 12,
                'description' => 'পারিবারিক বাসভবন, বাগান সহ',
            ],
            [
                'house_owner_id' => 2,
                'name' => 'চট্টগ্রাম টাওয়ার',
                'address' => '৫৫/এ, আগ্রাবাদ',
                'city' => 'চট্টগ্রাম',
                'state' => 'চট্টগ্রাম বিভাগ',
                'zip_code' => '৪১০০',
                'total_floors' => 8,
                'total_flats' => 32,
                'description' => 'বিলাসবহুল উঁচু ভবন, সমুদ্র দৃশ্য সহ',
            ],
            [
                'house_owner_id' => 3,
                'name' => 'সিটি সেন্টার প্লাজা',
                'address' => '৪২/সি, বনানী',
                'city' => 'ঢাকা',  
                'state' => 'ঢাকা বিভাগ',
                'zip_code' => '১২১৩',
                'total_floors' => 10,
                'total_flats' => 40,
                'description' => 'বাণিজ্যিক ও আবাসিক মিশ্র ব্যবহারের ভবন',
            ],
            [
                'house_owner_id' => 3,
                'name' => 'গ্রিন ভ্যালি রেসিডেন্স',
                'address' => '১২৫/ডি, সিলেট সদর',
                'city' => 'সিলেট',
                'state' => 'সিলেট বিভাগ',
                'zip_code' => '৩১০০',
                'total_floors' => 4,
                'total_flats' => 16,
                'description' => 'পরিবেশ বান্ধব আবাসিক কমপ্লেক্স',
            ],
            [
                'house_owner_id' => 4,
                'name' => 'মেট্রো হাইটস',
                'address' => '৭৮/ডি, উত্তরা, সেক্টর-৭',
                'city' => 'ঢাকা',
                'state' => 'ঢাকা বিভাগ',
                'zip_code' => '১২৩০',
                'total_floors' => 6,
                'total_flats' => 24,
                'description' => 'আধুনিক ভবন, মেট্রো স্টেশনের কাছে',
            ]
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
