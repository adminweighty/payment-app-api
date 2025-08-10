<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TicketConfirmation extends Mailable
{
    public $tickets;
    public $transaction;

    public $event;

    public function __construct(array $tickets, $transaction, $event)
    {
        $this->tickets = $tickets;
        $this->transaction = $transaction;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Your Ticket Confirmation')
            ->view('emails.ticket_confirmation');
    }
}
