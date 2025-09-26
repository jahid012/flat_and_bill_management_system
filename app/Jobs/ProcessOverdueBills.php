<?php

namespace App\Jobs;

use App\Mail\OverdueBillNotification;
use App\Models\Bill;
use App\Models\HouseOwner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ProcessOverdueBills implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        Bill::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        
        $houseOwners = HouseOwner::whereHas('bills', function ($query) {
            $query->where('status', 'overdue');
        })->get();

        foreach ($houseOwners as $houseOwner) {
            $overdueBills = $houseOwner->bills()
                ->where('status', 'overdue')
                ->with(['flat', 'building', 'billCategory'])
                ->get();

            if ($overdueBills->count() > 0 && $houseOwner->email) {
                Mail::to($houseOwner->email)
                    ->send(new OverdueBillNotification($overdueBills, $houseOwner));
            }
        }

        
        $this->notifyTenants();
    }

    /**
     * Notify tenants about their overdue bills.
     */
    private function notifyTenants(): void
    {
        $overdueBills = Bill::where('status', 'overdue')
            ->with(['flat.currentTenant', 'building', 'billCategory'])
            ->get();

        $tenantBills = $overdueBills->groupBy('flat.current_tenant_id')
            ->filter(function ($bills, $tenantId) {
                return $tenantId && $bills->first()->flat->currentTenant->email;
            });

        foreach ($tenantBills as $bills) {
            $tenant = $bills->first()->flat->currentTenant;
            if ($tenant && $tenant->email) {
                Mail::to($tenant->email)
                    ->send(new OverdueBillNotification($bills, $tenant));
            }
        }
    }
}
