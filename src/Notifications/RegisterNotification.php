<?php


namespace LaravelClass\LaraAuth\Notifications;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class RegisterNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private string $guard;

    public function __construct(string $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        if (config('laraAuth.email.builtinTemplate'))
        {

            $actionLink = URL::temporarySignedRoute(config('laraAuth.email.verifyEmail.actionLink.'.$this->guard)
                ,(new Carbon())->addMinutes(config('laraAuth.email.verifyEmail.linkExpire.'.$this->guard)),
            array_combine(array_values(config('laraAuth.email.verifyEmail.actionLinkParams.'.$this->guard)),[$notifiable->id,sha1($notifiable->email)]));

            return (new MailMessage)
                ->from(config('laraAuth.email.verifyEmail.from.'.$this->guard),config('laraAuth.email.verifyEmail.name.'.$this->guard))
                ->subject(config('laraAuth.email.verifyEmail.subject.'.$this->guard))
                ->line('The introduction to the notification.')
                ->action('Notification Action', $actionLink)
                ->line('Thank you for using our application!');
        }
        return (new MailMessage())
            ->view(config('laraAuth.email.customTemplateView.verifyEmailTemplate.'.$this->guard))->with(['user'=>$notifiable]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
