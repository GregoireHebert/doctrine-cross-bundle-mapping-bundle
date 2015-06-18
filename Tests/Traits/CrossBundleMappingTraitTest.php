<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\Tests\Traits;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class CrossBundleMappingTraitTest extends \PHPUnit_Framework_TestCase
{
    public $classTest;

    public function setUp(){
        $this->classTest = new TestClass();
    }

    public function testUndefinedScalarAttribute()
    {
        $this->assertNull($this->classTest->getScalarAttribute());

        $this->classTest->setScalarAttribute('test');
        $this->assertEquals('test', $this->classTest->getScalarAttribute());
    }

    public function testUndefinedBooleanAttribute()
    {
        $this->assertNull($this->classTest->getBooleanAttribute());
        $this->assertFalse($this->classTest->isBooleanAttribute());

        $this->classTest->setBooleanAttribute(true);
        $this->assertEquals(true, $this->classTest->getBooleanAttribute());
        $this->assertEquals(true, $this->classTest->isBooleanAttribute());

        $this->classTest->setBooleanAttribute('test');
        $this->assertEquals('test', $this->classTest->getBooleanAttribute());
        $this->assertEquals(false, $this->classTest->isBooleanAttribute());
    }

    public function testUndefinedArrayAttribute()
    {
        $this->assertNull($this->classTest->getArrayAttributes());

        $this->classTest->setArrayAttributes(array(1,2,3));
        $this->assertEquals(array(1,2,3), $this->classTest->getArrayAttributes());

        $this->classTest->addArrayAttribute('test');
        $this->assertEquals(array(1,2,3,'test'), $this->classTest->getArrayAttributes());

        $this->classTest->addArrayAttribute('test2');
        $this->classTest->addArrayAttribute('test');
        $this->assertEquals(array(1,2,3,'test','test2','test'), $this->classTest->getArrayAttributes());

        $this->classTest->removeArrayAttribute('test');
        $this->assertEquals(array(0=>1,1=>2,2=>3,4=>'test2',5=>'test'), $this->classTest->getArrayAttributes());

        $this->classTest->removeArrayAttribute('test');
        $this->assertEquals(array(0=>1,1=>2,2=>3,4=>'test2'), $this->classTest->getArrayAttributes());
    }

    public function testUndefinedArrayCollectionAttribute()
    {
        $this->assertNull($this->classTest->getCollectionAttributes());

        $ac = new ArrayCollection(array(1,2,3));

        $this->classTest->setCollectionAttributes($ac);
        $this->assertEquals($ac, $this->classTest->getCollectionAttributes());

        $this->classTest->addCollectionAttribute('test');
        $this->assertEquals(new ArrayCollection(array(1,2,3,'test')), $this->classTest->getCollectionAttributes());

        $this->classTest->addCollectionAttribute('test2');
        $this->classTest->addCollectionAttribute('test');
        $this->assertEquals(new ArrayCollection(array(1,2,3,'test','test2')), $this->classTest->getCollectionAttributes());

        $this->classTest->removeCollectionAttribute('test');
        $this->assertEquals(new ArrayCollection(array(0=>1,1=>2,2=>3,4=>'test2')), $this->classTest->getCollectionAttributes());
    }
}