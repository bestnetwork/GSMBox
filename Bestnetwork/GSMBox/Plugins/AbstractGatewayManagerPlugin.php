<?php

namespace Bestnetwork\GSMBox\Plugins;

abstract class AbstractGatewayManagerPlugin implements GatewayManagerPluginInterface {
    
    /**
     * Gateway name from config
     *
     * @var string
     */
    protected $gateway_name;

    /**
     * Constructor
     * 
     * @param string $gateway_name
     */
    public function __construct( $gateway_name ){
        $this->gateway_name = $gateway_name;
    }

    /**
     * Destruct
     */
    public function __destruct(){
        $this->disconnect();
    }
    
    /**
     * Connect to the gateway
     */
    abstract protected function connect();
    
    /**
     * Diconnect from the gateway
     */
    abstract protected function disconnect();
}