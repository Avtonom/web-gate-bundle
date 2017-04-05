<?php

namespace Avtonom\WebGateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function _addParametersNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('parameters');

        $node
            ->defaultValue(null)
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('parameters')
            ->prototype('array')
            ->children()
            ->arrayNode('web_gate')
            ->children()
                ->scalarNode('login')->defaultValue(null)->end()
                ->scalarNode('password')->defaultValue(null)->end()
                ->scalarNode('soap.environment')->defaultValue('dev')->end()
                ->integerNode('soap.connection_timeout')->defaultValue(15)->end()
                ->integerNode('logger.logging_max_files')->defaultValue(0)->end()
                ->integerNode('logger.logging_level')->defaultValue(100)->end()

            ->end()

            ->end()
            ->end()
        ;

        return $node;
    }

    public function _getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('web_gate');

        $rootNode
            ->children()
            ->scalarNode('soap.environment')->defaultValue('dev')->end()

            ->arrayNode('web_gate')

                ->children()
                ->scalarNode('soap.environment')->defaultValue('dev')->end()

                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('web_gate')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('login')->defaultValue(null)->end()
                ->scalarNode('password')->defaultValue(null)->end()
                ->scalarNode('soap.environment')->defaultValue('dev')->end()
                ->integerNode('soap.connection_timeout')->defaultValue(15)->end()
                ->integerNode('logger.logging_max_files')->defaultValue(0)->end()
                ->integerNode('logger.logging_level')->defaultValue(100)->end()
            ->end();
        /*$treeBuilder->root('request')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('api')
                ->children('array')
                    ->scalarNode('host')->defaultValue(null)->end()
                    ->scalarNode('resource')->defaultValue(null)->end()
                    ->scalarNode('login')->defaultValue(null)->end()
                    ->scalarNode('password')->defaultValue(null)->end()
                ->end()
            ->end();*/

        return $treeBuilder;
    }
}
