<?php

namespace Laravelpress\PluginSystem;


interface Plugin
{
    /**
     * Get structure for plugin info
     * @return mixed
     */
    public function info();

    /**
     * Bootstrap plugin
     * @return mixed
     */
    public function boot();

    /**
     * Get plugin name
     * @return string
     */
    public function getName();
}