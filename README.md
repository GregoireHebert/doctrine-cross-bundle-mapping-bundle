Doctrine Cross Bundle Mapping
==================
----------

This bundle is a solution to cross bundle association mapping.

This bundle brings you a way to associate two entities that belong
to two different bundles without breaking the decoupling and still run them independently.

[![Build Status](https://travis-ci.org/GregoireHebert/doctrine-cross-bundle-mapping-bundle.svg?branch=master)](https://travis-ci.org/GregoireHebert/doctrine-cross-bundle-mapping-bundle)

----------

### Use case

Assuming we have a UserBundle and a ForumBundle. 
if a User can be a Publisher, a Publisher could be used as an autonomous entity. 
As such I'd very much prefer these two do not share any hard links.

Creating the association mapping from the User entity to the Publisher entity creates a hard dependency.
As soon as the ForumBundle is disabled Doctrine throws errors that Publisher is not within any of its registered namespaces.

Since Doctrine is a bridge connecting the entities and the database, 
we can manipulate the way it understand how information will be stored and add dynamic mapping.

### Installation

Updating your composer.json 

``` Json
{
     ...
    "require": {
        ...
        "gheb/doctrine-cross-bundle-mapping-bundle": "^1.0@dev"
    }
     ...
}
```
and then

``` bash
$ composer update
```

OR

``` bash
$ composer require gheb/doctrine-cross-bundle-mapping-bundle
```


### Configuration

There is two way of using the bundle.

The first one is known as resolve_target_entities and is something already brought to us by doctrine.
Example of use can be found [here](http://symfony.com/en/doc/current/cookbook/doctrine/resolve_target_entity.html)
The only difference is that if a target entity does not exists, the mapping is not made.
There will be no error thrown.

The second way, is slightly different.
Instead of setting you mapping configuration with your entire entity mapping configuration,
you will set it in your config.yml.
You may want your extra configuration to be in your app/config.yml but it make sense to be in your bundle. (Acme/UserBundle/Resources/config/config.yml)

Here is an example of configuration

You have a UserBundle and a ForumBundle. Both can be used on it's own.
But by now i want them to communicate.
From the User i want to get the Publisher, (and then get everything he published)
or from the Publisher i want to get the User, (and then get his mail or his name)

``` yaml
#app/config/config.yml

doctrine_cross_bundle_mapping:
    mapping:
        Acme\UserBundle\Entity\User:
            oneToOne:
                publisher:
                    targetEntity: Acme\ForumBundle\Entity\Publisher
                    mappedBy: user
        Acme\ForumBundle\Entity\Publisher:
            oneToOne:
                user:
                    targetEntity: Acme\UserBundle\Entity\User
                    inversedBy: publisher
                    joinColumn:
                        name: user_id
                        referenceColumnName: id
```

This being set, Doctrine knows the mapping association.
But you need to update your schema. Check it before ;)

``` bash
$ php app/console doctrine:schema:update --dump-sql
$ php app/console doctrine:schema:update --force
```

### Attributes Access

So far we don't really need to do anything else.
But we will take it further.

PHP is really (too much ?) flexible and allows us to do this : 

``` php
$object = new Class();

$object->randomAttribute = 'myValue';
var_dump($object->randomAttribute); // myValue
```

But so far we've been taught to do this :

``` php
$object = new Class();

$object->setRandomAttribute('myValue');
var_dump($object->getRandomAttribute()); // myValue
```

And from the perspective where the User might be linked to the Publisher, 
there is no reason to write any accessor into it.

So for keeping the decoupling, just use a Trait.


``` php
namespace Acme\UserBundle\Entity;

use Gheb\Bundle\DoctrineCrossBundleMappingBundle\Traits\CrossBundleMappingTrait;

class User {
    use CrossBundleMappingTrait;
    ...
}
```

And then you can do as you would do 

``` php
$object = new Class();


// Scalar and Object

var_dump($object->getScalarAttribute()); // null
$object->setScalarAttribute('myValue');
var_dump($object->getScalarAttribute()); // myValue



// Boolean

var_dump($object->getBooleanAttribute()); // false
$object->setBooleanAttribute(true);
var_dump($object->getBooleanAttribute()); // true
var_dump($object->isBooleanAttribute()); // true

$object->setBooleanAttribute('test);
var_dump($object->getBooleanAttribute()); // test
var_dump($object->isBooleanAttribute()); // false



// Array

var_dump($object->getArrayAttributes()); // null
$object->setArrayAttributes(array(1,2,3));
var_dump($object->getArrayAttributes()); // array(1,2,3)

// When using addArrayAttribute, recognize the add and transform ArrayAttribute in arrayAttributes
$object->addArrayAttribute('test');
var_dump($object->getArrayAttributes()); // array(1,2,3,'test')

$object->addArrayAttribute('test2');
var_dump($object->getArrayAttributes()); // array(1,2,3,'test','test2)

$object->addArrayAttribute('test');
var_dump($object->getArrayAttributes()); // array(1,2,3,'test','test2,'test')

// When using removeArrayAttribute, recognize the remove and transform ArrayAttribute in arrayAttributes
$object->removeArrayAttribute('test');
var_dump($object->getArrayAttributes()); // array(0=>1, 1=>2, 2=>3, 4=>'test2, 5=>'test')

$object->removeArrayAttribute('test');
var_dump($object->getArrayAttributes()); // array(0=>1, 1=>2, 2=>3, 4=>'test2)



// ArrayCollection (or anything Traversable)

var_dump($object->getTraversableAttributes()); // null
$object->setTraversableAttributes(new ArrayCollection(array(1,2,3)));
var_dump($object->getTraversableAttributes()); // ArrayCollection(array(1,2,3))

// When using addArrayAttribute, recognize the add and transform ArrayAttribute in arrayAttributes
$object->addTraversableAttribute('test');
var_dump($object->getTraversableAttributes()); // ArrayCollection(array(1,2,3,'test'))

$object->addTraversableAttribute('test2');
var_dump($object->getTraversableAttributes()); // ArrayCollection(array(1,2,3,'test','test2))

$object->addTraversableAttribute('test');
var_dump($object->getTraversableAttributes()); // ArrayCollection(array(1,2,3,'test','test2))

// When using removeArrayAttribute, recognize the remove and transform ArrayAttribute in arrayAttributes
$object->removeTraversableAttribute('test');
var_dump($object->getTraversableAttributes()); // ArrayCollection(array(1,2,3,'test2))

```

### WARNING 

With great power comes great responsibility...
This prevent any misUse of any undeclared Method.

So you might want to extend you standard class and defines those accessor anyway.

``` php
namespace Acme\UserBundle\Entity;

use User as BaseUser;

class User extends BaseUser 
{
    protected $publisher;
    
    public function getPublisher();
    
    public function setPublisher();
    ...
}
```