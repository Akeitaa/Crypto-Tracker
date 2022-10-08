<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Transaction extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 10;$i < 20;$i++)
        {
            $transaction = new \App\Entity\Transaction();
            $date = '2022-10-' . $i;
            $transaction->setCreatedAt(new \DateTimeImmutable($date));
            $transaction->setPrice(rand(10000, 100000));
            $transaction->setQuantity(rand(1, 8));
            $crypto = ["BTC","ETH","XRP"];
            shuffle($crypto);
            $transaction->setCrypto(current($crypto));
            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
