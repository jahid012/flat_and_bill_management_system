<?php

namespace Database\Seeders;

use App\Models\BillCategory;
use App\Models\Building;
use Illuminate\Database\Seeder;

class BillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::where('is_active', true)->get();

        if ($buildings->isEmpty()) {
            $this->command->error('No active buildings found. Please run BuildingSeeder first.');
            return;
        }

                $categories = [
            [
                'name' => 'মাসিক ভাড়া',
                'description' => 'ফ্ল্যাটের নিয়মিত মাসিক ভাড়া',
                'icon' => 'house'
            ],
            [
                'name' => 'বিদ্যুৎ বিল',
                'description' => 'মাসিক বিদ্যুৎ ব্যবহারের চার্জ',
                'icon' => 'lightning'
            ],
            [
                'name' => 'পানির বিল',
                'description' => 'মাসিক পানি সরবরাহ ও ব্যবহারের চার্জ',
                'icon' => 'droplet'
            ],
            [
                'name' => 'গ্যাস বিল',
                'description' => 'মাসিক গ্যাস সংযোগ ও ব্যবহারের চার্জ',
                'icon' => 'fire'
            ],
            [
                'name' => 'রক্ষণাবেক্ষণ ফি',
                'description' => 'মাসিক ভবন রক্ষণাবেক্ষণ ও কমন এলাকার চার্জ',
                'icon' => 'wrench'
            ],
            [
                'name' => 'পার্কিং ফি',
                'description' => 'মাসিক পার্কিং স্থান ভাড়ার চার্জ',
                'icon' => 'car'
            ],
            [
                'name' => 'ইন্টারনেট ও কেবল',
                'description' => 'মাসিক ইন্টারনেট ও কেবল টিভির চার্জ',
                'icon' => 'wifi'
            ],
        ];

        
        foreach ($buildings as $building) {
            foreach ($categories as $category) {
                BillCategory::create([
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'icon' => $category['icon'],
                    'building_id' => $building->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
