<?php

namespace App\Mail;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillPaidNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bill;

    /**
     * Create a new message instance.
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bill Payment Received - ' . $this->bill->billCategory->name . ' for ' . $this->bill->bill_month,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bill-paid',
            with: [
                'bill' => $this->bill,
                'flat' => $this->bill->flat,
                'building' => $this->bill->building,
                'category' => $this->bill->billCategory,
                'tenant' => $this->bill->flat->currentTenant,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
