<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Registered $event): void
    {
        $user = $event->user;
        if (!$user || !$user->email) {
            return;
        }
        Mail::to($user->email)->queue(new WelcomeMail($user));
    }
}
