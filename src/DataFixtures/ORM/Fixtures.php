<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setEmail('admin@admin.com')
            ->setPassword('$10$p6NmK1j.dcbWOkU/n423F.EMsoWoGBTDuitggbACHR/LgEPooIYRa')
            ->setCountry('VE')
            ->setPhoto('');

        $manager->persist($user);
        $manager->flush();
    }
}