<?php

namespace Bestnetwork\GSMBox\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddGatewayPluginsPass implements CompilerPassInterface {
    
    public function process( ContainerBuilder $container ){
        if( $container->has('gsm_box.plugin_manager') ){
            $gateways = $container->getParameter('gsm_box.gateways');
            foreach ($gateways as $name => $gateway) {
                $plugins = $container->findTaggedServiceIds('gsm_box.gateway.plugin');
                $manager = sprintf('gsm_box.gateways.%s.manager', $name);
                $definition = $container->findDefinition($manager);
                foreach ($plugins as $id => $args) {
                    $definition->addMethodCall('addPlugin', array(new Reference($id)));
                }
            }
        }
    }
}