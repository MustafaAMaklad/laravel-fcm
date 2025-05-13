<?php

namespace Src\Facades;

use Illuminate\Support\Facades\Facade;
use Src\Services\FcmService;

class Fcm extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return FcmService::class;
    }
}