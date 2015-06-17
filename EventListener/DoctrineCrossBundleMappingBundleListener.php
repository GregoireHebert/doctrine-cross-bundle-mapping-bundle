<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;
use Symfony\Component\DependencyInjection\Container;

/**
 * The DoctrineCrossBundleMappingListener will create the bridge fields and associations.
 *
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class DoctrineCrossBundleMappingBundleListener
{
    /**
     * The class analyzer.
     *
     * @var ClassAnalyzer
     */
    private $classAnalyzer;

    /**
     * The Container
     * @var Container
     */
    private $container;

    /**
     * The mapping
     * @var array
     */
    private $mapping;

    /**
     * @param ClassAnalyzer $classAnalyzer The class analyzer.
     * @param Container $container The container.
     */
    public function __construct(ClassAnalyzer $classAnalyzer, Container $container)
    {
        $this->classAnalyzer = $classAnalyzer;
        $this->container = $container;
        $this->mapping = $this->getParameter('doctrine_cross_bundle_mapping.config')['mapping'];
    }

    /**
     * Gets the class analyzer.
     *
     * @return ClassAnalyzer
     */
    protected function getClassAnalyzer()
    {
        return $this->classAnalyzer;
    }


    /**
     * Get parameters from the service container
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * Checks whether the provided entity is supported.
     *
     * @param ClassMetadata $classMetadata The metadata.
     *
     * @return bool
     */
    public function isEntitySupported(ClassMetadata $classMetadata)
    {
        return (isset($this->mapping[$classMetadata->getName()]));
    }


    public function addMapping(ClassMetadata $classMetadata, $mappings, $type)
    {
        foreach($mappings as $name=>$mapping) {

            // Silently skip mapping the association if the related entity is missing
            if (class_exists($mapping['targetEntity']) === false) {
                continue;
            }

            if (isset($mapping['joinColumn']) && $mapping['joinColumn']['enabled']){
                unset($mapping['joinColumn']['enabled']);
            } else {
                unset($mapping['joinColumn']);
            }

            $mapping['fieldName'] = $name;

            switch ($type) {
                case 'manyToMany':
                    $classMetadata->mapManyToMany($mapping);
                    break;
                case 'manyToOne':
                    $classMetadata->mapManyToOne($mapping);
                    break;
                case 'oneToMany':
                    $classMetadata->mapOneToMany($mapping);
                    break;
                case 'oneToOne':
                    $classMetadata->mapOneToOne($mapping);
                    break;
            }
        }
    }

    /**
     * Loads the required metadata.
     *
     * @param LoadClassMetadataEventArgs $eventArgs The event arguments.
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        if ($this->isEntitySupported($classMetadata)) {
            foreach ($this->mapping[$classMetadata->getName()] as $type=>$mappings){
                $this->addMapping($classMetadata, $mappings, $type);
            }
        }
    }
}
