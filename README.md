
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

## use ajax request

```javascript
<script src="{{asset('laraAuthAjax.js')}}"></script>
<script>
    const formInputs = ['email','password','password_confirmation'];
    const logic = function (laraAuthResponse){
        console.log(laraAuthResponse);
    }
    const obj = new LaraAuthAjax(logic,formInputs);
```
just put above cods into you're template;

laraAuthAjax class cunstructor take two arguments;the first argument is a closure that define you're logic after response comes from the server;you can define the parametr for the closure to get the json response!
the second argument is the name of the inputs that you want to send to the server;if you dont have any,just let it go!


## Authors

- [@blackhole](https://github.com/laravelclass)

