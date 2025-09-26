<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OverdueBillNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $overdueBills;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct($overdueBills, $recipient)
    {
        $this->overdueBills = $overdueBills;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Overdue Bills Reminder - ' . count($this->overdueBills) . ' Bill(s) Overdue',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.overdue-bill',
            with: [
                'overdueBills' => $this->overdueBills,
                'recipient' => $this->recipient,
                'totalOverdueAmount' => $this->overdueBills->sum(function ($bill) {
                    return $bill->amount + $bill->due_amount - $bill->paid_amount;
                }),
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
