<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($userDetail){
        $this->user = $userDetail;
    }

    public function build()
    {
        return $this->markdown('emails.welcome')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Welcome to '.(env('APP_NAME') != '' ? env('APP_NAME') : 'Musioo'))
            ->with([
                'url' => $this->user['url'],
                'app_name' => (env('APP_NAME') != '' ? env('APP_NAME') : 'Musioo'),
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'password' => $this->user['password']
            ]);
    }
}
