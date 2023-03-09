<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotify extends Notification
{
    use Queueable;
    public $mailMsg;
    
    public function __construct($mailMsg)
    {
        $this->mailMsg = json_decode($mailMsg);
    }

    
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

   
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '.$notifiable->name.',')
                    ->line('Your payment '.$this->mailMsg->amount.' has been successfully done.')
                    ->line('Your transaction id is : '.$this->mailMsg->txn_id)
                    ->line('Thank You');
    }

    
    public function toArray($notifiable)
    {
        return [
            'data' => 'Your payment '.$this->mailMsg->amount.' has been successfully done.Your transaction id is : '.$this->mailMsg->txn_id
        ];
    }
}
