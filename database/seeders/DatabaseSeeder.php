<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->call(AdminSeeder::class);
            $this->call(HouseOwnerSeeder::class);
            $this->call(BuildingSeeder::class);
            $this->call(FlatSeeder::class);
            $this->call(TenantSeeder::class);
            $this->call(BillCategorySeeder::class);
            $this->call(BillSeeder::class);
            

        } finally {
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
       
    }
}
