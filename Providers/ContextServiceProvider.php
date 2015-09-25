<?php namespace App\Providers;

use App\Support\Context;
use Illuminate\Support\ServiceProvider;


/**
 * ContextServiceProvider
 *
 * @author       Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class ContextServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindShared('app.context', function ($app)
        {
            return new Context($app);
        });
    }
}