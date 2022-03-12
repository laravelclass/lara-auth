<?php


namespace LaravelClass\LaraAuth\Middleware;


use Illuminate\Http\Request;

class LaraAuthVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next, $guard = 'web')
    {
        return $request->user()->hasVerifiedEmail() ? redirect()->intended(config('laraAuth.middleware.verifyEmail.'.$guard.'.redirectIfCanNotVerifyEmail')) : $next($request);
    }
}
