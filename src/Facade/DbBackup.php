<?php

namespace Salman\DbBackup\Facades;

use Illuminate\Support\Facades\Facade;

class DbBackup extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DbBackup';
    }

}
