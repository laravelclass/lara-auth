<?php


namespace LaravelClass\LaraAuth\Notifications;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private array $credentials;
    private string $guard;

    public function __construct(array $credentials , string $guard)
    {
        $this->credentials = $credentials;

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
            $actionLink = URL::temporarySignedRoute(config('laraAuth.email.resetPassword.actionLink.'.$this->guard)
                ,(new Carbon())->addMinutes(config('laraAuth.email.resetPassword.linkExpire.'.$this->guard)),
            array_combine(array_values(config('laraAuth.email.resetPassword.actionLinkParams.'.$this->guard))
                ,[$this->credentials['token'],$this->credentials['email']]));

            return (new MailMessage)
                ->from(config('laraAuth.email.resetPassword.from.'.$this->guard),config('laraAuth.email.resetPassword.name.'.$this->guard))
                ->subject(config('laraAuth.email.resetPassword.subject.'.$this->guard))
                ->line('The introduction to the notification.')
                ->action('Notification Action', $actionLink)
                ->line('Thank you for using our application!');
        }
        return (new MailMessage())
            ->view(config('laraAuth.email.customTemplateView.resetPasswordTemplate.'.$this->guard))->with(['user'=>$notifiable]);
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
