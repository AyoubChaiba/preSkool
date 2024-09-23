<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof UserCreated) {
            Mail::to($event->user->email)->send(new WelcomeUserMail($event->user, $event->password, $event->role));
        }

        if ($event instanceof UserUpdated && $event->password) {
            Mail::to($event->user->email)->send(new WelcomeUserMail($event->user, $event->password, $event->user->role));
        }
    }
}
