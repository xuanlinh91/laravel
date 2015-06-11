<?php namespace Shaphira\Facades;

use Illuminate\Support\Facades\Facade;

class LogViewer extends Facade {

    protected static function getFacadeAccessor() {

        return 'logviewer';
    }
}
