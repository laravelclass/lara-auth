<?php


namespace LaravelClass\LaraAuth\Service\Implementation\CoreTraits;


use Illuminate\Support\Facades\Auth;

trait Logout
{
    public function logOutCore($guard = 'web')
    {
        Auth::guard($guard)->logout();

        session()->invalidate();

        session()->regenerateToken();

        return [
            'response' => redirect()->route(config('laraAuth.auth.validation.logout.redirectRoute.'.$guard)),
            'state' => true
        ];

    }
}
