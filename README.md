
# Custom Laravel Authentication

custom laravel authentication by one of mr rahmanian's classes' crew.
if you want  to contact mr ali rahmanian:

website: alirahmanian.com

instagram: ali.rahmanian.s.a



## what's the point of this packages?

we had so many limited with laravel starter kit authentication.so i decided to make an authentication package that removes many of laravel auth package limits.


## Features

- support multiple guard with diffrent configs
- support queue for notifications
- has hook system for define before and after logic
- support ajax request with an js abstract
- can change the response depend on state of package with hook
- can change all of response messages
- can define message for ajax request
- can define logic after ajax response
- can custom email's subject,from and.....
- can use custom email template or builtin email template
- can use you're own auth template



## Deployment

for install this package:

```bash
  composer require laravelclass/lara-auth
```

for serve config and ajax abstract:


```bash
  php artisan laraAuth:up
```

after serve the package,laraAuth.php and laraAuthAjax.js copied to configs and public directory


```bash
  configs/laraAuth.php
  public/laraAuthAjax.js
```

this package needs password_resets table to complete the reset password;
so dont for get to :

```bash
  php artisan migrate
```

## change the confiuration

in configs/laraAuth.php you can define you're configuration for sending email,auth,messages,attributes,rules,queue and...

## how to use

```php
LaraAuth::makeAjax();

LaraAuth::before(function (){
     //you're logic.if you return the response, the package will not continue and server sends you're response! 
});

LaraAuth::after(function ($status){
       //you're logic.if you return the response, the package will not continue and server sends you're response! 
       // $status is the final state of the package core before sending the response;
});

     return LaraAuth::register('Admin','admins');
```
default guard is web and the default databaseModelName is User;

dont forget that you're model has to extends Authenticatable and use HasApiTokens, HasFactory, Notifiable;

if you want to send registration email,just implement MustVerifyEmail

## methods available on LaraAuth Facade
```php
LaravelClass\LaraAuth\Service\Facade\LaraAuth::before(\Closure $beforeClosure)

LaravelClass\LaraAuth\Service\Facade\LaraAuth::after(\Closure $afterClosure)

LaravelClass\LaraAuth\Service\Facade\LaraAuth::register($dbName = 'users' , $guard = 'web' , $extraData = null)

LaravelClass\LaraAuth\Service\Facade\LaraAuth::sendVerifyUserEmailNotification($guard= 'web', $dbName = 'User' , $notifiable = null)

LaravelClass\LaraAuth\Service\Facade\LaraAuth::canUserResetPassword($guard = 'web')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::verifyEmailLink($guard = 'web')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::verifyPasswordResetLink($guard = 'web' , $dbName = 'User')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::login($guard = 'web')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::logOut($guard = 'web')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::sendResetPasswordNotification($guard = 'web' , $dbName = 'User')

LaravelClass\LaraAuth\Service\Facade\LaraAuth::makeAjax()

```

## how to make hook system

```php
LaraAuth::before(function (){
     //you're logic.if you return the response, the package will not continue and server sends you're response! 
});

LaraAuth::after(function ($status){
       //you're logic.if you return the response, the package will not continue and server sends you're response! 
       // $status is the final state of the package core before sending the response;
});

 return LaraAuth::register('Admin','admins');
```
if you want to controll on before and after of package'core execution,you can use above methods;
before() and after() methods accepts a closure that define youre logic.if you want to stop the execution of the package,you have to return an response object;
in after() method,you're closure can accept the boolean the injected from the package core;this bool determine that if the operation was successful or not;

## how to register,login and logout

```php
return LaraAuth::login($guard = 'web')

return LaraAuth::logOut($guard = 'web')

return LaraAuth::register($dbName = 'users' , $guard = 'web' , $extraData = null)

```
all of the methods accepts $guard that determine the which guard you want to use from the gurad that you determined in auth.php config file

the default value for $guard and $dbname is web and User

if you want to determine the $dbName,you have to pass the Model Name like User

register() method accepts $extraData that you can pass an array of extra key value field that you want to insert to database in registration;

## how to verify the email and reset the password

```php
return sendVerifyUserEmailNotification($guard= 'web', $dbName = 'User' , $notifiable = null)

return verifyEmailLink($guard = 'web')

return sendResetPasswordNotification($guard = 'web' , $dbName = 'User')

return verifyPasswordResetLink($guard = 'web' , $dbName = 'User')

```
if you want to send verify email or reset email,just return send...notificatoin method;

if you want to verify the email links,just return verify.... methods;

## how to use package's middlewares

```php
->middleware('laraAuthEmail')  ==> for verify email 

->middleware('laraAuthResetPassword')  ==> for password reset link
```

## how to use queue for sending notification

```php
'queue' => [
             'web' => [
                    'state' => true,
                    'delay' => 0,
                    'connection' => 'database',
                    'queue' => 'default'
                ],
                'admins' => [
                    'state' => false,
                    'delay' => 0,
                    'connection' => 'database',
                    'queue' => 'default'
                ],

```

you can find in laraAuth.php under the configs directory;

queue config is available for both of reset password notification and verifyEmailLink

you can determine it even for guards!!

dont forget to setup and start youre queue worker and queue database;


## use ajax request

```javascript
//put meta tag for csrf token in <heads> tag
<meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">

//put below codes after all of youre template tags
<script src="{{asset('laraAuthAjax.js')}}"></script>
<script>
    const formInputs = ['email','password','password_confirmation'];
    const logic = function (laraAuthResponse){
        console.log(laraAuthResponse);
    }
    const obj = new LaraAuthAjax(logic,formInputs);
    </script>

    //in youre action route in laravel,call this method before any of operation methods:

    LaraAuth::makeAjax();

    //then you can use one of package method like:
     
     return LaraAuth::login()
```
just put above cods into you're template;

laraAuthAjax class cunstructor take two arguments;the first argument is a closure that define you're logic after response comes from the server;you can define the parametr for the closure to get the json response!
the second argument is the name of the inputs that you want to send to the server;if you dont have any,just let it go!


## Authors

- [@blackhole](https://github.com/laravelclass)

