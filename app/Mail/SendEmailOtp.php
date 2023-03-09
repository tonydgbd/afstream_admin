<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
    */
    public function __construct($userDetail){        
        $this->user = $userDetail;
    }

    public function build()
    {
        return $this->markdown('emails.sendOtp')
            ->from(env('MAIL_FROM_ADDRESS')) 
            ->subject('Reset Password Notification')
            ->with([
                'app_name' => (env('APP_NAME') != '' ? env('APP_NAME') : 'Musioo'),
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'otp' => $this->user['otp']
            ]);
    }

    
}
