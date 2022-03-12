<?php


namespace LaravelClass\LaraAuth\Service\Implementation\CoreTraits;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use function LaravelClass\LaraAuth\helpers\makeMessage;

trait Login
{
    private function loginCore($guard = 'web')
    {
        $fieldsToAttempt = config('laraAuth.auth.validation.login.fieldsToAttempt.'.$guard);

        $rememberMeField = request()->get(config('laraAuth.auth.validation.login.rememberMeField.'.$guard)) ? 1 : 0;

        if (Auth::guard($guard)->attempt(request()->only($fieldsToAttempt),$rememberMeField))
        {
            session()->regenerate();

            session()->regenerateToken();

            if (config('laraAuth.auth.validation.login.redirectRoute.'.$guard.'.intended'))
            {
                $response = redirect()->intended(route(config('laraAuth.auth.validation.login.redirectRoute.'.$guard.'.route')));
            }
            else
            {
                $response = redirect()->route(config('laraAuth.auth.validation.login.redirectRoute.'.$guard.'.route'));
            }

            $successMessage = makeMessage(config('laraAuth.auth.validation.login.successfulMessage.'.$guard));

            return [
                'response' => $response,
                'successfulMessage' => $successMessage->getMessages(),
                'state' => true
            ];

        }

        $errorMessage = makeMessage(config('laraAuth.auth.validation.login.errorMessage.'.$guard));

        return [
            'response' => back()->withErrors($errorMessage),
            'errorMessages' => $errorMessage->getMessages(),
            'state' => false
        ];

    }
}
