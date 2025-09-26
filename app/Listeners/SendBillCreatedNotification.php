<?php

namespace App\Listeners;

use App\Events\BillCreated;
use App\Mail\BillCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBillCreatedNotification implements ShouldQueue
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
    public function handle(BillCreated $event): void
    {
        $bill = $event->bill;
        $bill->load(['flat.currentTenant', 'building.houseOwner', 'billCategory']);

        
        if ($bill->building->houseOwner && $bill->building->houseOwner->email) {
            Mail::to($bill->building->houseOwner->email)
                ->send(new BillCreatedNotification($bill));
        }

        
        if ($bill->flat->currentTenant && $bill->flat->currentTenant->email) {
            Mail::to($bill->flat->currentTenant->email)
                ->send(new BillCreatedNotification($bill));
        }
    }
}
