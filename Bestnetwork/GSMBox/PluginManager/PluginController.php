<?php

namespace Bestnetwork\GSMBox\PluginManager;

use Bestnetwork\GSMBox\Plugin\PluginInterface;

class PluginManager implements PluginManagerInterface
{
    private $plugins;

    public function __construct(){
        $this->plugins = array();
    }

    public function addPlugin( PluginInterface $plugin ){
        $this->plugins[] = $plugin;
    }

    protected function getPlugin( $pluginName ){
        if( !isset($this->plugins[$pluginName]) ){
            throw new Exceptions\PluginNotFoundException($pluginName);
        }

        return $this->plugins[$pluginName];
    }
}