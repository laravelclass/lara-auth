<?php


namespace LaravelClass\LaraAuth\Listeners;


use Illuminate\Support\Facades\Notification;
use LaravelClass\LaraAuth\Events\RegisterUser;
use LaravelClass\LaraAuth\Notifications\RegisterNotification;

class RegisterListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(RegisterUser $event)
    {
        $queueRules = config('laraAuth.email.verifyEmail.queue.'.$event->guard);

        if ($queueRules['state'])
        {
            $delay = now()->addMinutes($queueRules['delay']);

            $queue = $queueRules['queue'];

            $connection = $queueRules['connection'];

            Notification::send($event->userObject,(new RegisterNotification($event->guard)

            )->delay($delay)->onQueue($queue)->onConnection($connection));
        }
        else
        {
            Notification::sendNow($event->userObject,new RegisterNotification($event->guard));
        }
    }
}
