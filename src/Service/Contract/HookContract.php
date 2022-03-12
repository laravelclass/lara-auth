<?php


namespace LaravelClass\LaraAuth\Service\Contract;


interface HookContract
{
    public function before(\Closure $beforeClosure);

    public function after(\Closure $afterClosure);

}
