<?php


namespace LaravelClass\LaraAuth\Service\Facade;


use Illuminate\Support\Facades\Facade;

/**
 * Class LaraAuth
 * @package LaravelClass\CustomAuth\Service\Facade
 * @method static bool before(\Closure $beforeClosure)
 * @method static bool after(\Closure $beforeClosure)
 * @method static register($dbName = 'users' , $guard = 'web' , $extraData = null)
 * @method static sendVerifyUserEmailNotification($guard= 'web', $dbName = 'User' , $notifiable = null)
 * @method static canUserResetPassword($guard = 'web')
 * @method static verifyEmailLink($guard = 'web')
 * @method static verifyPasswordResetLink($guard = 'web' , $dbName = 'User')
 * @method static login($guard = 'web')
 * @method static logOut($guard = 'web')
 * @method static sendResetPasswordNotification($guard = 'web' , $dbName = 'User')
 * @method static makeAjax()
 */
class LaraAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaraAuth';
    }
}
