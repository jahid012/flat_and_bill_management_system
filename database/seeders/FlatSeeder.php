<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Flat;
use Illuminate\Database\Seeder;

class FlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::all();

        if ($buildings->isEmpty()) {
            return;
        }

        $flatTypes = ['1BHK', '2BHK', '3BHK', '4BHK', 'Studio', 'Other'];
        $flatStatuses = ['occupied', 'vacant', 'maintenance'];

        foreach ($buildings as $building) {
            
            for ($i = 1; $i <= $building->total_flats; $i++) {
                $floor = ceil($i / 4); 
                $flatNumber = $floor . str_pad($i % 4 ?: 4, 2, '0', STR_PAD_LEFT);

                if ($building->type === 'commercial') {
                    $flatNumber = 'C-' . $flatNumber;
                    $type = 'Other'; 
                } else {
                    $type = $flatTypes[array_rand($flatTypes)];
                }

                $status = $flatStatuses[array_rand($flatStatuses)];
                $weights = [70, 25, 5]; 
                $rand = rand(1, 100);
                if ($rand <= $weights[0]) {
                    $status = 'occupied';
                } elseif ($rand <= $weights[0] + $weights[1]) {
                    $status = 'vacant';
                } else {
                    $status = 'maintenance';
                }

                Flat::create([
                    'flat_number' => $flatNumber,
                    'building_id' => $building->id,
                    'house_owner_id' => $building->house_owner_id,
                    'floor' => $floor,
                    'type' => $type,
                    'area_sqft' => rand(500, 2000),
                    'rent_amount' => rand(800, 3000),
                    'is_occupied' => $status === 'occupied',
                    'flat_owner_name' => $status === 'occupied' ? fake()->name() : null,
                    'flat_owner_phone' => $status === 'occupied' ? fake()->phoneNumber() : null,
                    'flat_owner_email' => $status === 'occupied' ? fake()->unique()->safeEmail() : null,
                ]);
            }
        }
    }
}
