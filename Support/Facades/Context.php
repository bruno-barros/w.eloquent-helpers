<?php namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * Context
 *
 * @author       Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class Context extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'app.context';
    }
}