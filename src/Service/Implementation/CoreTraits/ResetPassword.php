<?php


namespace LaravelClass\LaraAuth\Service\Implementation\CoreTraits;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use LaravelClass\LaraAuth\Notifications\RegisterNotification;
use LaravelClass\LaraAuth\Notifications\ResetPasswordNotification;
use function LaravelClass\LaraAuth\helpers\makeMessage;

trait ResetPassword
{
    public function sendResetPasswordNotificationCore($guard = 'web' , $dbName = 'User')
    {
        $rules = config('laraAuth.auth.validation.forgotPassword.rules.'.$guard);

        $rules[array_key_first($rules)][] = 'exists:\App\Models\\'.ucfirst($dbName).',email';

        $messages = config('laraAuth.auth.validation.forgotPassword.messages.'.$guard);

        $customAttributes = config('laraAuth.auth.validation.forgotPassword.customAttributes.'.$guard);

        $validated = Validator::make(request()->all(),$rules , $messages , $customAttributes);

        if ($validated->fails())
        {
            return  [
                'response' => back()->withErrors($validated->errors()),
              'errorMessages' =>  $validated->errors()->getMessages(),
                'state' => false
            ];
        }

        $dbName = '\App\Models\\'.$dbName;

        $validated = $validated->safe()->all();

        $notifiable = $dbName::query()->where('email',$validated[array_key_first($validated)])->first();

        $token = Hash::make($notifiable->email);

        DB::table('password_resets')->where('email',$notifiable->email)->delete();

        DB::table('password_resets')->insert(['email'=>$notifiable->email,'token' => $token,'created_at' => Carbon::now()]);

        $queueRules = config('laraAuth.email.resetPassword.queue.'.$guard);

        if ($queueRules['state'])
        {
            $delay = Carbon::now()->addMinutes($queueRules['delay']);

            $connection = $queueRules['connection'];

            $queue = $queueRules['queue'];

            Notification::send($notifiable,(new ResetPasswordNotification(['token'=>$token,'email'=>$notifiable->email],$guard))->delay($delay)->onQueue($queue)->onConnection($connection));
        }
        else
        {
            Notification::sendNow($notifiable,new ResetPasswordNotification(['token'=>$token,'email'=>$notifiable->email],$guard));
        }

        $msgKey = array_key_first(config('laraAuth.auth.validation.forgotPassword.successfulMessage.'.$guard));

        $successfulMessages = makeMessage(config('laraAuth.auth.validation.forgotPassword.successfulMessage.'.$guard));

        return [
            'response' => back()->with($msgKey,config('laraAuth.auth.validation.forgotPassword.successfulMessage.'.$guard)[$msgKey]),
            'successfulMessages' => $successfulMessages->getMessages(),
            'state' => true
        ];
    }
}
