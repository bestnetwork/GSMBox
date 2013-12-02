<?php

namespace Bestnetwork\GSMBox\PluginManager;

use Bestnetwork\GSMBox\Plugin\PluginInterface;

interface PluginManagerInterface {

    public function addPlugin( PluginInterface $plugin );

    public function getPlugin( $plaginName );
}