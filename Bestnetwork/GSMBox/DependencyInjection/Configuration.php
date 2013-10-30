<?php

namespace Bestnetwork\GSMBox\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gsm_box');

        $rootNode
            ->fixXmlConfig('gateway')
            ->beforeNormalization()
                ->ifTrue( function( $v ){ return is_array($v) && !array_key_exists('gateways', $v); })
                ->then( function( $v ){
                    $gateway = array();
                    
                    foreach( $v as $key => $value ){
                        if( 'default_gateway' == $key ){
                            continue;
                        }
                        $gateway[$key] = $v[$key];
                        unset($v[$key]);
                    }
                    
                    $v['default_gateway'] = isset($v['default_gateway']) ? (string) $v['default_gateway'] : 'default';
                    $v['gateways'] = array($v['default_gateway'] => $gateway);

                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('default_gateway')->defaultValue('default')->end()
                ->arrayNode('gateways')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->defaultValue('localhost')->end()
                            ->integerNode('port')->defaultNull()->end()
                            ->integerNode('timeout')->defaultValue(10)->end()
                            ->scalarNode('username')->defaultNull()->end()
                            ->scalarNode('password')->defaultNull()->end()
                            ->scalarNode('manager')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
