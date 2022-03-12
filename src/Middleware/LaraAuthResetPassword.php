<?php


namespace LaravelClass\LaraAuth\Middleware;


use Illuminate\Http\Request;

class LaraAuthResetPassword
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
        return $request->hasValidSignature() && \Illuminate\Support\Facades\DB::table('password_resets')->where('email',request()->get('email'))->exists()

            ? $next($request) : redirect()->route(config('laraAuth.middleware.resetPassword.'.$guard.'.redirectIfCanNotResetPassword'));
    }
}
