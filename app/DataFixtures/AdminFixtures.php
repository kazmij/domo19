<?php

namespace App\DataFixtures;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = $manager->getRepository(User::class)->findOneByUsername('admin');
        if(!$admin) {
            $admin = new User();
            $admin->setEmail('pawel.kazmierczak@jaaqob.pl');
            $admin->setUsername('admin');
            $admin->setFirstname('Pawel');
            $admin->setLastname('Kazmierczak');
            $admin->setSuperAdmin(true);
            $admin->setPlainPassword('wakcylion19');
            $admin->setEnabled(true);

            $manager->persist($admin);
            $manager->flush();
        }
    }
}
