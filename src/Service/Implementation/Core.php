<?php


namespace LaravelClass\LaraAuth\Service\Implementation;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use LaravelClass\LaraAuth\Service\Contract\HookContract;

class Core implements HookContract
{
    use Hook,Auth;

    private static bool $result = true;

    private static bool $makeAjax = false;

    public function __call(string $name, array $arguments)
    {
        $beforeClosureResponse = static::beforeClosureExecution();

        if ($this->isResponseTrue($beforeClosureResponse))
        {
            return $beforeClosureResponse;
        }

        $mainMethod = $name.'Core';

        $response = $this->$mainMethod(... $arguments);

        if ($response['state'] == false)
        {
            static::$result = false;
        }

        $closureResponse = static::afterClosureExecution(static::$result);

        if ($this->isResponseTrue($closureResponse))
        {
            return $closureResponse;
        }

        return $this->makeResponse($response);
    }

    private function isResponseTrue($response)
    {
        if ($response instanceof Response || $response instanceof RedirectResponse)
        {
            return true;
        }
        return  false;
    }

    private function makeResponse(array $response)
    {
        if (static::$makeAjax)
        {
            return \response()->json(
                array_filter($response,fn($index,$key) => $key != 'response',ARRAY_FILTER_USE_BOTH)
            );
        }
        return $response['response'];
    }

}
