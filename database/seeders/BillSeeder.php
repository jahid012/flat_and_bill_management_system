<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillCategory;
use App\Models\Flat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flats = Flat::where('is_occupied', true)->get();
        $billCategories = BillCategory::all()->groupBy('building_id');

        if ($flats->isEmpty()) {
            $this->command->error('No occupied flats found. Please run FlatSeeder and TenantSeeder first.');
            return;
        }

        if ($billCategories->isEmpty()) {
            $this->command->error('No bill categories found. Please run BillCategorySeeder first.');
            return;
        }

        $billNumber = 1000;

        foreach ($flats as $flat) {
            
            $buildingCategories = $billCategories->get($flat->building_id);
            if (!$buildingCategories) continue;

            for ($monthsBack = 6; $monthsBack >= 0; $monthsBack--) {
                $billDate = Carbon::now()->subMonths($monthsBack)->startOfMonth();
                $dueDate = $billDate->copy()->addDays(15);

                
                $this->createMonthlyRentBill($flat, $buildingCategories, $billDate, $dueDate, $billNumber);
                $billNumber++;
                
                // Randomly add utility bills (80% chance)
                if (rand(1, 100) <= 80) {
                    $billNumber = $this->createUtilityBills($flat, $buildingCategories, $billDate, $dueDate, $billNumber);
                }

                // Occasionally add maintenance or repair bills (20% chance)
                if (rand(1, 100) <= 20) {
                    $this->createMaintenanceBill($flat, $buildingCategories, $billDate, $dueDate, $billNumber);
                    $billNumber++;
                }

                // Add late payment fees for overdue bills (10% chance for past bills)
                if ($monthsBack > 1 && rand(1, 100) <= 10) {
                    $this->createLateFeeBill($flat, $buildingCategories, $billDate, $dueDate, $billNumber);
                    $billNumber++;
                }
            }
        }
    }

    private function createMonthlyRentBill($flat, $categories, $billDate, $dueDate, &$billNumber)
    {
        $rentCategory = $categories->where('name', 'মাসিক ভাড়া')->first();
        if (!$rentCategory) return;

        $bill = Bill::create([
            'flat_id' => $flat->id,
            'bill_category_id' => $rentCategory->id,
            'building_id' => $flat->building_id,
            'amount' => $flat->rent_amount,
            'due_date' => $dueDate,
            'bill_month' => $billDate->format('Y-m'),
            'status' => $this->getBillStatus($dueDate),
            'created_by' => $flat->house_owner_id,
            'created_at' => $billDate,
            'updated_at' => $billDate,
        ]);

        
        if ($bill->status === 'paid') {
            $this->markBillAsPaid($bill, $dueDate);
        }
    }

    private function createUtilityBills($flat, $categories, $billDate, $dueDate, $billNumber)
    {
        $utilityCategories = ['বিদ্যুৎ বিল', 'পানির বিল', 'গ্যাস বিল', 'ইন্টারনেট ও কেবল'];
        
        foreach ($utilityCategories as $categoryName) {
            if (rand(1, 100) <= 70) { 
                $category = $categories->where('name', $categoryName)->first();
                if (!$category) continue;

                
                $existingBill = Bill::where([
                    'flat_id' => $flat->id,
                    'bill_category_id' => $category->id,
                    'bill_month' => $billDate->format('Y-m')
                ])->first();
                
                if ($existingBill) continue; 

                
                $baseAmounts = [
                    'বিদ্যুৎ বিল' => rand(2000, 8000),
                    'পানির বিল' => rand(800, 2500),
                    'গ্যাস বিল' => rand(1200, 3500),
                    'ইন্টারনেট ও কেবল' => rand(1500, 3000),
                ];
                $amount = $baseAmounts[$categoryName] ?? rand(1000, 3000);

                $bill = Bill::create([
                    'flat_id' => $flat->id,
                    'bill_category_id' => $category->id,
                    'building_id' => $flat->building_id,
                    'amount' => $amount,
                    'due_date' => $dueDate,
                    'bill_month' => $billDate->format('Y-m'),
                    'status' => $this->getBillStatus($dueDate),
                    'created_by' => $flat->house_owner_id,
                    'created_at' => $billDate,
                    'updated_at' => $billDate,
                ]);

                if ($bill->status === 'paid') {
                    $this->markBillAsPaid($bill, $dueDate);
                }
                
                $billNumber++;
            }
        }
        
        return $billNumber;
    }

    private function createMaintenanceBill($flat, $categories, $billDate, $dueDate, &$billNumber)
    {
        $maintenanceCategories = ['রক্ষণাবেক্ষণ ফি', 'পার্কিং ফি'];
        $categoryName = $maintenanceCategories[array_rand($maintenanceCategories)];
        
        $category = $categories->where('name', $categoryName)->first();
        if (!$category) return;

        
        $existingBill = Bill::where([
            'flat_id' => $flat->id,
            'bill_category_id' => $category->id,
            'bill_month' => $billDate->format('Y-m')
        ])->first();
        
        if ($existingBill) return; 

        $amount = rand(1500, 5000); 

        $bill = Bill::create([
            'flat_id' => $flat->id,
            'bill_category_id' => $category->id,
            'building_id' => $flat->building_id,
            'amount' => $amount,
            'due_date' => $dueDate,
            'bill_month' => $billDate->format('Y-m'),
            'status' => $this->getBillStatus($dueDate),
            'created_by' => $flat->house_owner_id,
            'created_at' => $billDate,
            'updated_at' => $billDate,
        ]);

        if ($bill->status === 'paid') {
            $this->markBillAsPaid($bill, $dueDate);
        }
    }

    private function createLateFeeBill($flat, $categories, $billDate, $dueDate, &$billNumber)
    {
        
        $category = $categories->where('name', 'রক্ষণাবেক্ষণ ফি')->first();
        if (!$category) return;

        
        $existingBill = Bill::where([
            'flat_id' => $flat->id,
            'bill_category_id' => $category->id,
            'bill_month' => $billDate->format('Y-m')
        ])->first();
        
        if ($existingBill) return; 

        $bill = Bill::create([
            'flat_id' => $flat->id,
            'bill_category_id' => $category->id,
            'building_id' => $flat->building_id,
            'amount' => rand(500, 1500), 
            'due_date' => $dueDate,
            'bill_month' => $billDate->format('Y-m'),
            'status' => $this->getBillStatus($dueDate),
            'created_by' => $flat->house_owner_id,
            'created_at' => $billDate,
            'updated_at' => $billDate,
        ]);

        if ($bill->status === 'paid') {
            $this->markBillAsPaid($bill, $dueDate);
        }
    }

    private function getBillStatus($dueDate)
    {
        $now = Carbon::now();
        
        if ($dueDate->isFuture()) {
            
            return 'unpaid';
        } elseif ($dueDate->isPast()) {
            $rand = rand(1, 100);
            if ($rand <= 70) {
                return 'paid';
            } elseif ($rand <= 90) {
                return 'overdue';
            } else {
                return 'unpaid';
            }
        } else {
            
            return rand(1, 100) <= 50 ? 'paid' : 'unpaid';
        }
    }

    private function markBillAsPaid($bill, $dueDate)
    {
        $paymentMethods = ['cash', 'bank_transfer', 'cheque', 'online'];
        $paymentDate = $dueDate->copy()->subDays(rand(0, 10)); 

        $bill->update([
            'status' => 'paid',
            'paid_date' => $paymentDate,
            'paid_amount' => $bill->amount,
            'payment_method' => $paymentMethods[array_rand($paymentMethods)],
            'transaction_id' => 'TXN' . rand(100000, 999999),
            'payment_notes' => 'Payment received on time. Thank you!',
        ]);
    }
}
