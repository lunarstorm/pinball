<?php

namespace Vio\Pinball\Facades;

use Illuminate\Support\Facades\Facade;

class Pinball extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pinball';
    }
}
