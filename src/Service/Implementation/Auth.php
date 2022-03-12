<?php


namespace LaravelClass\LaraAuth\Service\Implementation;


use LaravelClass\LaraAuth\Service\Implementation\CoreTraits\Login;
use LaravelClass\LaraAuth\Service\Implementation\CoreTraits\Logout;
use LaravelClass\LaraAuth\Service\Implementation\CoreTraits\Register;
use LaravelClass\LaraAuth\Service\Implementation\CoreTraits\ResetPassword;
use LaravelClass\LaraAuth\Service\Implementation\CoreTraits\VerifyLink;

trait Auth
{
    use Register,VerifyLink,Login,Logout,ResetPassword;
}
