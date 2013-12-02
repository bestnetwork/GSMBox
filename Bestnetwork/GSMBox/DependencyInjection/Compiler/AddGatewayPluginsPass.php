<?php

namespace Bestnetwork\GSMBox\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

use Bestnetwork\GSMBox\PluginManager\Exceptions\PluginNotFoundException;

class AddGatewayPluginsPass implements CompilerPassInterface {
    
    public function process( ContainerBuilder $container ){
        if( $container->getParameter('gsm_box.gateways') ){
            $plugins = $container->findTaggedServiceIds('gsm_box.gateway');
            
            $gateways = $container->getParameter('gsm_box.gateways');
            foreach( $gateways as $name => $gatewayParams ){
                $gatewayManagerID = 'gsm_box.gateway.' . $gatewayParams['manager'];
                
                if( isset($plugins[$gatewayManagerID]) ){
                    
                    /*$definitionDecorator = new DefinitionDecorator('gsm_box.gateway.abstract');*/
                    
                    $container
                        ->getDefinition($gatewayManagerID)
                        ->addArgument($name)
                        ->addArgument(array(
                            'host' => $gatewayParams['host'],
                            'port' => $gatewayParams['port'],
                            'user' => $gatewayParams['username'],
                            'pass' => $gatewayParams['password'],
                            'timeout' => $gatewayParams['timeout']
                        ));
                }else{
                    throw new PluginNotFoundException('Plugin "' . $gatewayManagerID . '" does not exists or cannot be loaded.');
                }
            }
        }
    }
}