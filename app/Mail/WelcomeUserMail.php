<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $role)
    {
        $this->user = $user;
        $this->password = $password;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Welcome to the Platform')
                    ->view('emails.welcome');
    }
}
