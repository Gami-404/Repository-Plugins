<?php

namespace Laravelpress\PluginSystem;

use Throwable;

class PluginException extends \Exception
{
    /**
     * Plugin name which mean in exception
     * @var int
     */
    protected $pluginName;
    /**
     * @var bool depended problems problems
     */
    protected $dependedProblems = false;
    /**
     * PluginException constructor.
     * @param string $message
     * @param int $pluginName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $pluginName, $code = 0, Throwable $previous = null)
    {
        $this->pluginName=$pluginName;
        parent::__construct($message, $code, $previous);

    }
    /**
     * @return string
     */
    public function __toString()
    {
        return parent::__toString() . ' For plugin' . $this->pluginName;
    }

}