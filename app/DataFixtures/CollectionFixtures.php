<?php

namespace App\DataFixtures;

use Application\Sonata\ClassificationBundle\Entity\Collection;
use Application\Sonata\ClassificationBundle\Entity\Context;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CollectionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $contexts = [
            'default', 'menu', 'offer', 'offer_definitions', 'page', 'project_categories'
        ];
        foreach($contexts as $context) {
            $contextObject = $manager->getRepository(Context::class)->find($context);
            if(!$contextObject) {
                $contextObject = new Context();
                $contextObject->setEnabled(true);
                $contextObject->setName($context);
                $contextObject->setId($context);
                $manager->persist($contextObject);
            }
        }

        $manager->flush();

        $collections = [
          [
             'name' => 'mainMenu',
             'context' => 'menu'
          ]
        ];

        foreach($collections as $collection) {
            $collectionObject = $manager->getRepository(Collection::class)->findOneByName($collection['name']);
            if(!$collectionObject) {
                $collectionObject = new Collection();
                $collectionObject->setName($collection['name']);
                $collectionObject->setContext($manager->getRepository(Context::class)->find($collection['context']));
                $collectionObject->setEnabled(true);
                $manager->persist($collectionObject);
            }
        }

        $manager->flush();
    }
}
