<?php

namespace Bestnetwork\GSMBox\Plugins;

interface GatewayManagerPluginInterface extends PluginInterface {
    public function getFirmwareInfo();
    public function getGatewayInfo();
    public function getSIMCount();
}