<?php

namespace Bestnetwork\GSMBox\PluginManager\Exceptions;

class PluginNotFoundException extends PluginManagerException {
    
    public function __construct( $pluginName ) {
        parent::__construct('Plugin "' . $pluginName . '" not found.');
    }
}