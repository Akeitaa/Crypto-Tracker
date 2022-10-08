<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DailyValuation extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 10;$i < 20;$i++)
        {
            $dailyValuation = new \App\Entity\DailyValuation();
            $date = '2022-10-' . $i;
            $dailyValuation->setCreatedAt(new \DateTimeImmutable($date));
            $dailyValuation->setAmount(rand(10000, 100000));
            $manager->persist($dailyValuation);
        }

        $manager->flush();
    }
}
