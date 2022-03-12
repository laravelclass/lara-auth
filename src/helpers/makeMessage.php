<?php
namespace LaravelClass\LaraAuth\helpers;

use Illuminate\Support\MessageBag;

function makeMessage($configMessage){

    $message = new MessageBag();

    $key = array_key_first($configMessage);

    $message->add($key,$configMessage[$key]);

    return $message;
}
