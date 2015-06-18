<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;
use Gheb\Bundle\DoctrineCrossBundleMappingBundle\EventListener\DoctrineCrossBundleMappingBundleListener;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class DoctrineCrossBundleMappingListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMappingAssociationNonExistingClass()
    {
        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $mapping = array(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass' => array(
                'manyToMany' => array(
                    'entity' => array(
                        'targetEntity' => 'Acme\Foo\Entity',
                        'fieldName' => 'randomName'
                    )
                )
            )
        );

        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilder->disableOriginalConstructor();
        $container = $containerBuilder->getMock();
        $container->expects($this->once())->method('getParameter')->will($this->returnValue(array('mapping'=>$mapping)));

        $classMetadata->associationMappings[] = $mapping;
        $classMetadata->name = 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass';

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);
        $classAnalyzer = new ClassAnalyzer();

        $listener = new DoctrineCrossBundleMappingBundleListener($classAnalyzer, $container);
        $listener->loadClassMetadata($args);
    }

    public function testAddMappingAssociationExistingClassMTM()
    {
        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->once())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $mapping = array(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass' => array(
                'manyToMany' => array(
                    'entity' => array(
                        'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
                        'fieldName' => 'randomName'
                    )
                )
            )
        );

        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilder->disableOriginalConstructor();
        $container = $containerBuilder->getMock();
        $container->expects($this->once())->method('getParameter')->will($this->returnValue(array('mapping'=>$mapping)));

        $classMetadata->associationMappings[] = $mapping;

        $classMetadata->name = 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass';

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);
        $classAnalyzer = new ClassAnalyzer();

        $listener = new DoctrineCrossBundleMappingBundleListener($classAnalyzer, $container);
        $listener->loadClassMetadata($args);
    }

    public function testAddMappingAssociationExistingClassMTO()
    {
        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->once())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $mapping = array(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass' => array(
                'manyToOne' => array(
                    'entity' => array(
                        'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
                        'fieldName' => 'randomName'
                    )
                )
            )
        );

        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilder->disableOriginalConstructor();
        $container = $containerBuilder->getMock();
        $container->expects($this->once())->method('getParameter')->will($this->returnValue(array('mapping'=>$mapping)));

        $classMetadata->associationMappings[] = $mapping;

        $classMetadata->name = 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass';

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);
        $classAnalyzer = new ClassAnalyzer();

        $listener = new DoctrineCrossBundleMappingBundleListener($classAnalyzer, $container);
        $listener->loadClassMetadata($args);
    }

    public function testAddMappingAssociationExistingClassOTM()
    {
        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->once())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $mapping = array(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass' => array(
                'oneToMany' => array(
                    'entity' => array(
                        'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
                        'fieldName' => 'randomName'
                    )
                )
            )
        );

        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilder->disableOriginalConstructor();
        $container = $containerBuilder->getMock();
        $container->expects($this->once())->method('getParameter')->will($this->returnValue(array('mapping'=>$mapping)));

        $classMetadata->associationMappings[] = $mapping;

        $classMetadata->name = 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass';

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);
        $classAnalyzer = new ClassAnalyzer();

        $listener = new DoctrineCrossBundleMappingBundleListener($classAnalyzer, $container);
        $listener->loadClassMetadata($args);
    }

    public function testAddMappingAssociationExistingClassOTO()
    {
        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->once())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $mapping = array(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass' => array(
                'oneToOne' => array(
                    'entity' => array(
                        'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
                        'fieldName' => 'randomName'
                    )
                )
            )
        );

        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilder->disableOriginalConstructor();
        $container = $containerBuilder->getMock();
        $container->expects($this->once())->method('getParameter')->will($this->returnValue(array('mapping'=>$mapping)));

        $classMetadata->associationMappings[] = $mapping;

        $classMetadata->name = 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass';

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);
        $classAnalyzer = new ClassAnalyzer();

        $listener = new DoctrineCrossBundleMappingBundleListener($classAnalyzer, $container);
        $listener->loadClassMetadata($args);
    }
}