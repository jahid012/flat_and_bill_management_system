<?php

namespace App\Listeners;

use App\Events\BillPaid;
use App\Mail\BillPaidNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBillPaidNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(BillPaid $event): void
    {
        $bill = $event->bill;
        $bill->load(['flat.currentTenant', 'building.houseOwner', 'billCategory']);

        
        if ($bill->building->houseOwner && $bill->building->houseOwner->email) {
            Mail::to($bill->building->houseOwner->email)
                ->send(new BillPaidNotification($bill));
        }

        
        if ($bill->flat->currentTenant && $bill->flat->currentTenant->email) {
            Mail::to($bill->flat->currentTenant->email)
                ->send(new BillPaidNotification($bill));
        }
    }
}
