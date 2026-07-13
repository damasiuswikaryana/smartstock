<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class TicketCreated extends Notification
{
    use Queueable;

    private $customers; // LINE ADDED

    public function __construct($customers) // PARAMETER PASSED
    {
        $this->customers = $customers; // LINE ADDED
    }

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        // dd($this->customers);
        return (new SlackMessage)
            ->success()
            ->content('The ticket with code ' . $this->customers . ' with level has been created. Please check the ticket');
    }
}
