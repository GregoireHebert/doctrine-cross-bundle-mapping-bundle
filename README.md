Doctrine Cross Bundle Mapping
==================
----------

This bundle is a solution to cross bundle association mapping.

This bundle brings you a way to associate two entities that belong
to two different bundles without breaking the decoupling and still run them independently.

----------

### Use case

Assuming we have a UserBundle and a ForumBundle. 
if a User can be a Publisher, a Publisher could be used as an autonomous entity. 
As such I'd very much prefer these two do not share any hard links.

Creating the association mapping from the User entity to the Publisher entity creates a hard dependency.
As soon as the ForumBundle is disabled Doctrine throws errors that Publisher is not within any of its registered namespaces.

Since Doctrine is a bridge connecting the entities and the database, 
we can manipulate the way it understand how information will be stored and add dynamic mapping.

### Notes

Tested under specific configuration

``` Json
"php": ">=5.4",
"symfony/symfony": "~2.7",
"doctrine/orm": "~2.5",
"doctrine/doctrine-bundle": "~1.5",
"knplabs/doctrine-behaviors": "~1.0",

           
```

### Configuration

There is two way of using the bundle.

The first one is known as resolve_target_entities and is something already brought to us by doctrine.
Example of use can be found [here](http://symfony.com/en/doc/current/cookbook/doctrine/resolve_target_entity.html)
The only difference is that if a target entity does not exists, the mapping is not made.
There will be no error thrown.

The second one is bit less intrusive onto the entities.

``` yaml
#app/config/config.yml

#Doctrine Cross Bundle Mapping
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

The configuration brings informations to doctrine.
Now you need to update your database schema.

``` bash
$ php app/console doctrine:schema:update --dump-sql
$ php app/console doctrine:schema:update --force
```