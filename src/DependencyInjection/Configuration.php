<?php

declare(strict_types=1);

namespace AdgoalCommon\AlertingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @category AdgoalCommon\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @psalm-suppress PossiblyUndefinedMethod
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('adgoal_alerting');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('client_name')->info('for example events')->end()
                ->scalarNode('client_producer')->info('for example enqueue.client.events.producer')->end()
                ->scalarNode('storage_repository')->info('Should be instance of AdgoalCommon\Alerting\Domain\Repository\StorageRepositoryInterface')->end()
                ->scalarNode('delaytime')->end()
                ->scalarNode('project_name')->end()
            ->end()
            ->children()
                ->arrayNode('adapter')
                    ->children()
                        ->scalarNode('type')->end()
                        ->scalarNode('host')->end()
                        ->scalarNode('port')->end()
                        ->scalarNode('token')->end()
                        ->arrayNode('attributes')
                            ->children()
                                ->scalarNode('resource')->end()
                                ->scalarNode('service')->end()
                                ->scalarNode('environment')->end()
                                ->scalarNode('type')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end() // adapter
            ->end()
        ;

        return $treeBuilder;
    }
}
