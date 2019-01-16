<?php

namespace App\DataFixtures;

use Application\Sonata\ClassificationBundle\Entity\Category;
use Application\Sonata\ClassificationBundle\Entity\Context;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProjectsCategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $context = $manager->getRepository(Context::class)->find('project_categories');
        $categoriesData = [
            'Parterowe', 'Piętrowe', 'Z poddaszem', 'Tradycyjne', 'Drewniane', 'W promocji', 'Zabudowa bliźniacza', 'Garaże', 'Nowoczesne',
            'Małe', 'Średnie', 'Wille', 'Tanie w budowie', 'Na wąską działkę'
        ];

        foreach($categoriesData as $c) {
            $category = new Category();
            $category->setName($c);
            $category->setEnabled(true);
            $category->setContext($context);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
