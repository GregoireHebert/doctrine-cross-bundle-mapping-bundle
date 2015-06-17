<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * BridgeBundle configuration.
 *
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        return $treeBuilder
            ->root('doctrine_cross_bundle_mapping', 'array')
                ->children()
                    ->arrayNode('mapping')

                        // Defines the entity to be mapped
                        ->info("Configure as you would with doctrine ORM")
                        ->example("
doctrine_cross_bundle_mapping:
    mapping:
        Acme\Bundle\FooBundle\Entity\User:
            oneToOne:
                address:
                    targetEntity: Acme\Bundle\FooBundle\Entity\Address
                    inversedBy: user
                    joinColumn:
                        name: address_id
                        referenceColumnName: id
                    cascade: ['persist', 'update', 'remove']
        Acme\Bundle\FooBundle\Entity\Address:
            oneToOne:
                user:
                    targetEntity: Acme\Bundle\FooBundle\Entity\User
                    mappedBy: address")
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()

                                // Defines a oneToOne association
                                ->arrayNode('oneToOne')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('targetEntity')->end()
                                            ->scalarNode('mappedBy')->end()
                                            ->scalarNode('inversedBy')->end()

                                            ->arrayNode('joinColumn')
                                                ->canBeEnabled()
                                                ->children()
                                                    ->scalarNode('onDelete')->end()
                                                    ->scalarNode('onUpdate')->end()
                                                    ->scalarNode('name')->end()
                                                    ->scalarNode('referencedColumnName')->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('cascade')
                                                ->prototype('scalar')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()


                                // Defines a oneToMany association
                                ->arrayNode('oneToMany')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('targetEntity')->end()
                                            ->scalarNode('mappedBy')->end()
                                            ->scalarNode('fetch')->end()
                                            ->scalarNode('indexBy')->end()
                                            ->arrayNode('orderBy')
                                                ->useAttributeAsKey('name')
                                                    ->prototype('array')
                                                        ->children()
                                                        ->scalarNode('value')->isRequired()->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('cascade')
                                                ->prototype('scalar')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()


                                // Defines a manyToOne association
                                ->arrayNode('manyToOne')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('targetEntity')->end()
                                            ->scalarNode('inversedBy')->end()
                                            ->scalarNode('fetch')->end()
                                            ->booleanNode('orphanRemoval')->defaultFalse()->end()

                                            ->arrayNode('joinColumn')
                                                ->canBeEnabled()
                                                ->children()
                                                    ->scalarNode('name')->end()
                                                    ->scalarNode('referenceColumnName')->end()
                                                    ->booleanNode('nullable')->defaultFalse()->end()
                                                    ->scalarNode('onDelete')->end()
                                                    ->scalarNode('onUpdate')->end()
                                                    ->booleanNode('unique')->defaultFalse()->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('cascade')
                                                ->prototype('scalar')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()


                                // Defines a manyToOne association
                                ->arrayNode('manyToMany')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('targetEntity')->end()
                                            ->scalarNode('inversedBy')->end()
                                            ->scalarNode('mappedBy')->end()

                                            ->arrayNode('joinTable')
                                                ->children()
                                                    ->scalarNode('name')->end()
                                                    ->arrayNode('joinColumns')
                                                        ->useAttributeAsKey('name')
                                                        ->prototype('array')
                                                            ->children()
                                                                ->scalarNode('name')->end()
                                                                ->scalarNode('fetch')->end()
                                                                ->scalarNode('onDelete')->end()
                                                                ->scalarNode('onUpdate')->end()
                                                                ->scalarNode('referencedColumnName')->end()
                                                                ->booleanNode('nullable')->defaultFalse()->end()
                                                            ->end()
                                                        ->end()
                                                    ->end()
                                                    ->arrayNode('inverseJoinColumns')
                                                        ->useAttributeAsKey('name')
                                                        ->prototype('array')
                                                            ->children()
                                                                ->scalarNode('name')->end()
                                                                ->scalarNode('referencedColumnName')->end()
                                                                ->booleanNode('nullable')->defaultFalse()->end()
                                                                ->booleanNode('unique')->defaultFalse()->end()
                                                            ->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()

                                            ->arrayNode('cascade')
                                                ->prototype('scalar')->end()
                                            ->end()

                                            ->arrayNode('orderBy')
                                                ->useAttributeAsKey('name')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('value')->isRequired()->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()


                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end();
    }
}
