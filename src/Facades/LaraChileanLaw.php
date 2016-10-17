<?php

namespace Unforgivencl\LaraChileanLaw\Facades;

use Illuminate\Support\Facades\Facade;

class LaraChileanLaw extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Unforgivencl\LaraChileanLaw\LawsApi\ApiRequest';
    }
}
