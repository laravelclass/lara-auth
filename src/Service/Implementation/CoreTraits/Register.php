<?php


namespace LaravelClass\LaraAuth\Service\Implementation\CoreTraits;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use LaravelClass\LaraAuth\Events\RegisterUser;
use LaravelClass\LaraAuth\Notifications\RegisterNotification;
use LaravelClass\LaraAuth\Service\Facade\LaraAuth;
use LaravelClass\LaraAuth\Service\Implementation\Auth;
use function LaravelClass\LaraAuth\helpers\makeMessage;

trait Register
{
    private function registerCore($dbName = 'User' , $guard = 'web' , $extraData = [])
    {

        $rules = config('laraAuth.auth.validation.register.rules.'.$guard);

        $uniqueFields = config('laraAuth.auth.validation.register.uniqueField.'.$guard);

        foreach ($uniqueFields as $uniqueField)
        {
            $rules[$uniqueField][] = 'unique:\App\Models\\'.ucfirst($dbName).','.$uniqueField;
        }

        $messages = config('laraAuth.auth.validation.register.messages.'.$guard);

        $customAttributes = config('laraAuth.auth.validation.register.customAttributes.'.$guard);

        $validator = Validator::make(array_merge(\request()->all(),$extraData),$rules , $messages , $customAttributes);

        if ($validator->fails())
        {
            return [
                'errorMessages' => $validator->getMessageBag()->getMessages(),
                'state' => false,
                'response' => back()->withErrors($validator->errors())
            ];
        }

        $validated = $validator->safe()->all();

        $validated['password'] = Hash::make($validated['password']);

        $dbName = '\App\Models\\'.$dbName;

        if ($extraData && is_array($extraData))
        {
            $validated = array_merge($validated , $extraData);
        }

        if ($user = $dbName::query()->create($validated))
        {
            if (is_a($user,MustVerifyEmail::class))
            {
                $this->sendVerifyUserEmailNotificationCore($guard,$user);
            }

            \Illuminate\Support\Facades\Auth::guard($guard)->login($user);

            session()->regenerate();

            $successMessage = makeMessage(config('laraAuth.auth.validation.register.successfulMessages.'.$guard));

            return [
                'response' => redirect()->route(config('laraAuth.auth.validation.register.redirectRoute.'.$guard)),
                'successfulMessages' => $successMessage->getMessages(),
                'state' => true,
                'redirectTo' => route(config('laraAuth.auth.validation.register.redirectRoute.'.$guard))
            ];

        }

        $errorMessage = makeMessage(config('laraAuth.auth.validation.register.errorMessage.'.$guard));

        return [
            'response' => back()->withErrors($errorMessage),
            'errorMessages' => $errorMessage->getMessages(),
            'state' => false
        ];
    }

    private function sendVerifyUserEmailNotificationCore($guard = 'web', $notifiable = null)
    {
        if (!$notifiable)
        {
            $notifiable = \Illuminate\Support\Facades\Auth::guard($guard)->user();
        }
        RegisterUser::dispatch($notifiable,$guard);

        $msgKey = array_key_first(config('laraAuth.auth.validation.verifyEmail.successfulMessage.'.$guard));

        $successMessage = makeMessage(config('laraAuth.auth.validation.verifyEmail.successfulMessage.'.$guard));

        return [
            'response' => redirect()->route(config('laraAuth.auth.validation.verifyEmail.redirectAfterEmailSend.'.$guard))
                ->with($msgKey,config('laraAuth.auth.validation.verifyEmail.successfulMessage.'.$guard)[$msgKey]),

            'successfulMessages' => $successMessage->getMessages(),

            'redirectTo' => route(config('laraAuth.auth.validation.verifyEmail.redirectAfterEmailSend.'.$guard)),

            'state' => true
        ];
    }
}
