<?php

namespace Azeroglu\Bot\Facades;

use Illuminate\Support\Facades\Facade;

class Bot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bot';
    }
}
