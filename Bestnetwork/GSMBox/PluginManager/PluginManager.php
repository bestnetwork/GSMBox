<?php

namespace Bestnetwork\GSMBox\PluginManager;

use Bestnetwork\GSMBox\Plugin\PluginInterface;

class PluginManager implements PluginManagerInterface
{
    private $plugins;

    public function __construct(){
        $this->plugins = array();
    }

    /**
     * @param \Bestnetwork\GSMBox\Plugin\PluginInterface $plugin
     */
    public function addPlugin( PluginInterface $plugin ){
        $this->plugins[] = $plugin;
    }

    /**
     * @param string $pluginName
     * @return \Bestnetwork\GSMBox\Plugin\PluginInterface
     * @throws Exceptions\PluginNotFoundException
     */
    protected function getPlugin( $pluginName ){
        if( !isset($this->plugins[$pluginName]) ){
            throw new Exceptions\PluginNotFoundException('Cannot found "' . $pluginName . '" plugin.');
        }

        return $this->plugins[$pluginName];
    }
}