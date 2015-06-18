<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gheb\Bundle\DoctrineCrossBundleMappingBundle\EventListener\ResolveTargetEntityListener;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class ResolveTargetEntityListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testRemapAssociationNonExistingClass()
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
            'targetEntity' => 'Acme\Foo\Entity',
            'fieldName' => 'randomName'
        );

        $classMetadata->associationMappings[] = $mapping;

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);

        $listener = new ResolveTargetEntityListener();
        $listener->loadClassMetadata($args);
    }

    public function testRemapAssociationExistingClassMTM()
    {
        $manyToManyMapping = array(
            'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'fieldName' => 'randomNameManyToMany',
            'type' => ClassMetadata::MANY_TO_MANY
        );

        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->once())->method('mapManyToMany')->with($manyToManyMapping);
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $classMetadata->associationMappings[] = $manyToManyMapping;

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);

        $listener = new ResolveTargetEntityListener();
        $listener->addResolveTargetEntity(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            $manyToManyMapping
        );
        $listener->loadClassMetadata($args);
    }

    public function testRemapAssociationExistingClassMTO()
    {
        $manyToOneMapping = array(
            'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'fieldName' => 'randomNameManyToOne',
            'type' => ClassMetadata::MANY_TO_ONE
        );

        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->once())->method('mapManyToOne')->with($manyToOneMapping);
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $classMetadata->associationMappings[] = $manyToOneMapping;

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);

        $listener = new ResolveTargetEntityListener();
        $listener->addResolveTargetEntity(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            $manyToOneMapping
        );
        $listener->loadClassMetadata($args);
    }

    public function testRemapAssociationExistingClassOTM()
    {
        $oneToManyMapping = array(
            'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'fieldName' => 'randomNameOneToMany',
            'type' => ClassMetadata::ONE_TO_MANY
        );

        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->once())->method('mapOneToMany')->with($oneToManyMapping);
        $classMetadata->expects($this->never())->method('mapOneToOne');

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $classMetadata->associationMappings[] = $oneToManyMapping;

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);

        $listener = new ResolveTargetEntityListener();
        $listener->addResolveTargetEntity(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            $oneToManyMapping
        );
        $listener->loadClassMetadata($args);
    }

    public function testRemapAssociationExistingClassOTO()
    {
        $oneToOneMapping = array(
            'targetEntity' => 'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'fieldName' => 'randomNameOneToOne',
            'type' => ClassMetadata::ONE_TO_ONE
        );

        $classMetadataMockBuilder = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata');
        $classMetadataMockBuilder->disableOriginalConstructor();
        $classMetadataMockBuilder->setMethods(array('mapManyToMany', 'mapManyToOne', 'mapOneToMany', 'mapOneToOne'));

        $classMetadata = $classMetadataMockBuilder->getMock();
        $classMetadata->expects($this->never())->method('mapManyToMany');
        $classMetadata->expects($this->never())->method('mapManyToOne');
        $classMetadata->expects($this->never())->method('mapOneToMany');
        $classMetadata->expects($this->once())->method('mapOneToOne')->with($oneToOneMapping);

        $entityManagerInterfaceMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        $classMetadata->associationMappings[] = $oneToOneMapping;

        $args = new LoadClassMetadataEventArgs($classMetadata, $entityManagerInterfaceMock);

        $listener = new ResolveTargetEntityListener();
        $listener->addResolveTargetEntity(
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            'Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits\TestClass',
            $oneToOneMapping
        );
        $listener->loadClassMetadata($args);
    }
}