<?php
/**
 * Created by PhpStorm.
 * User: gami
 * Date: 17/08/17
 * Time: 06:13 م
 */

namespace Laravelpress\PluginSystem;


trait LaravelPluginTrait
{

    /**
     * @var String $name plugin 's name
     */
    protected $name;

    /**
     * @var String $path Plugin 's Absolute path
     */
    protected $path;

    /**
     * @var array $middlewares is Array of middlewares
     */
    protected $middlewares;

    /**
     * @var array $command is Array of Command line
     */
    protected $command;

    /**
     * @var ServiceProvider $baseProvider Loader Provider
     */
    protected $baseProvider;

    /**
     * @var array $providers of plugin relate provider
     */
    protected $providers;

    /**
     * Default Boot for plugin suitable for laravel
     * @return mixed
     */
    abstract protected function defaultBootstrap();
    /**
     * Load and inject plugin middlewares with Laravel
     * @return mixed
     */
    abstract protected function loadMiddlewares();
    /**
     * Load Plugin routes
     * @return mixed
     */
    abstract protected function loadRoutes();
    /**
     * Load and inject Plugin's Providers to laravel Lifecycle
     * @return mixed
     */
    abstract protected function loadProviders();

}