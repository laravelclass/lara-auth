<?php


namespace LaravelClass\LaraAuth\Service\Implementation;


trait Hook
{
    private static \Closure|null $beforeClosure = null;

    private static \Closure|null $afterClosure = null;

    public function before(\Closure $beforeClosure)
    {
        static::$beforeClosure = $beforeClosure;
    }

    public function after(\Closure $afterClosure)
    {
        static::$afterClosure = $afterClosure;
    }

    private static function beforeClosureExecution()
    {
        $beforeClsMethod = static::$beforeClosure;

        if ($beforeClsMethod instanceof \Closure)
        {
            return $beforeClsMethod();
        }

        return  '';
    }

    private static function afterClosureExecution(bool $result)
    {
        $afterClsMethod = static::$afterClosure;

        if ($afterClsMethod instanceof \Closure)
        {
            return $afterClsMethod($result);
        }
        return  '';
    }

    public function makeAjax()
    {
        static::$makeAjax = true;
    }
}
