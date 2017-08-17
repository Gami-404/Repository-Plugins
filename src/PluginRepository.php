<?php


namespace Laravelpress\PluginSystem;


class PluginRepository implements \Iterator
{

    /**
     * Plugin rows
     * @var array
     */
    protected $plugins = [];

    /**
     * Current Position used in iterator
     * @var int
     */
    protected $position = 0;

    /**
     * PluginRepository constructor.
     */
    public function __construct($plugin = null)
    {
        if (empty($plugin)) return;

        // If Given array of Plugins to customer
        if (is_array($plugin)) {
            $plugins = $plugin;
            foreach ($plugins as $plugin) {
                $this->attach($plugin);
            }
            return;
        }

        //attach plugin
        $this->attach($plugin);
    }

    /**
     * Attach plugin in repository
     * @param Plugin $plugin
     * @return PluginRepository
     */
    public function attach(Plugin $plugin)
    {
        $this->AddPluginRow($plugin, $plugin->getName());
        return $this;
    }

    /**
     * Detach Plugin form repository
     * @param  $plugin Object or plugin name
     * @return Plugin|null
     */
    public function detach($plugin)
    {
        return $this->removePluginRow($plugin);
    }

    /**
     * For Ask if plugin Existing in repository or not
     * @param $plugin
     * @return bool True of exit otherwise false
     */
    public function exist($plugin)
    {
        // If plugin object given in parameter
        if ($plugin instanceof Plugin) {
            foreach ($this->plugins as $loadedPlugin) {
                if ($loadedPlugin->plugin === $plugin) {
                    return true;
                }
            }
            return false;
        }


        return null === $this->getPlugin($plugin);
    }

    /**
     * Get Plugin Object
     * @param $name String
     * @return Plugin
     */
    public function getPlugin($name)
    {
        $pluginRow = $this->getPluginRowByname($name);
        return empty($pluginRow) ? $pluginRow : $pluginRow->plugin;
    }

    /**
     * Get all plugin Object
     * @return array
     */
    public function all()
    {
        return array_map(function ($plugin) {
            return $plugin->plugin;
        }, $this->plugins);
    }

    /**
     * Get all plugins name of all loaded plugins
     * @return array All plugins name
     */
    public function allName()
    {
        return array_map(function ($plugin) {
            return $plugin->pluginName;
        }, $this->plugins);
    }

    /**
     * Execute function internal plugin object
     * @param $method_name
     * @return $this
     * @throws \Laravelpress\PluginSystem\PluginException
     */
    public function PluginsInternalExecute($method_name)
    {
        array_walk($this->plugins, function ($plugin) use ($method_name) {
            if (!method_exists($plugin->plugin, $method_name)) {
                throw new PluginException('Method '
                    . get_class($plugin->plugin) . '->'
                    . $method_name . '() not exist in  ' . get_class($plugin->plugin));
            }
            $plugin->plugin->$method_name();
        });
        return $this;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->plugins[$this->position]->plugin;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->plugins[$this->position]->plaginName;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->plugins[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param $name
     * @return Plugin
     */
    public function __get($name)
    {
        return $this->getPlugin($name);
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        if (substr_compare($name, 'PluginInternalExecute', 0, 21)) {
            $suffix = substr($name, 21);
            $this->PluginsInternalExecute($suffix);
        }
    }

    /**
     * Get Plugin row name
     * @param $name
     * @return mixed
     */
    private function getPluginRowByname($name)
    {
        $filtered = array_filter($this->plugins,
            function ($plugin) use ($name) {
                if ($plugin->pluginName == $name) {
                    return $plugin->plugin;
                }
                return false;
            });
        return array_pop($filtered);
    }

    /**
     * @param Plugin $plugin
     * @param $pluginName
     */
    private function AddPluginRow(Plugin $plugin, $pluginName)
    {
        $row = new \StdClass();
        $plugin->plugin = $plugin;
        $plugin->pluginName = $pluginName;
        $this->plugins[] = $row;
    }

    /**
     * Delete Plugin row
     * @param $plugin
     * @return Plugin|null
     */
    private function removePluginRow($plugin)
    {
        $index = 0;
        // Delete Plugin if $plugin Parameter is Plugin Object
        if ($plugin instanceof Plugin) {
            foreach ($this->plugins as $loadedPlugin) {
                if ($loadedPlugin->plugin === $plugin) {
                    unset($this->plugins[$index]);
                    return $loadedPlugin;
                }
                $index++;
            }
            return null;
        }

        // Delete Plugin if $plugin Parameter is String ( Plugin name)
        foreach ($this->plugins as $loadedPlugin) {
            if ($loadedPlugin->pluginName === $plugin) {
                unset($this->plugins[$index]);
                return $loadedPlugin;
            }
            $index++;
        }
        return null;
    }
}

