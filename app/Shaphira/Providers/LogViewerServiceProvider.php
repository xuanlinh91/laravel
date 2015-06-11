<?php namespace Shaphira\Providers;

use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->bind('logviewer', 'Shaphira\LogViewer\LogViewer');
    }
}
