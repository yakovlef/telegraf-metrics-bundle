<?php

namespace Yakovlef\TelegrafMetricsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('telegraf_metrics');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('namespace')
                    ->defaultValue('app')
                ->end()
                ->arrayNode('client')
                    ->children()
                        ->scalarNode('url')
                        ->defaultValue('http://localhost:8086')
                    ->end()
                        ->integerNode('udpPort')
                        ->defaultValue(8125)
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
