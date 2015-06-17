<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * DoctrineCrossBundleMappingBundleExtension loads the DoctrineCrossBundleMapping configuration.
 *
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class DoctrineCrossBundleMappingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // Get default configuration of the bundle
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('doctrine_cross_bundle_mapping.config', $config);

        // load dependency injection config
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('listeners.yml');
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'doctrine_cross_bundle_mapping';
    }
}
