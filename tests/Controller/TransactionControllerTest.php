<?php

namespace App\Tests;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TransactionRepository $repository;
    private string $path = '/transaction/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Transaction::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des transactions');
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('AJOUTER', [
            'transaction[crypto]' => 'BTC',
            'transaction[quantity]' =>1.0,
            'transaction[price]' => 22000.00,
        ]);

        self::assertResponseRedirects(sprintf('%snew', $this->path));

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testEdit(): void
    {
        $fixture = new Transaction();
        $fixture->setCrypto('BTC');
        $fixture->setQuantity(2);
        $fixture->setPrice( 22344);

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('MODIFIER', [
            'transaction[crypto]' => 'ETH',
            'transaction[quantity]' => 1.0,
            'transaction[price]' => 1320,
        ]);

        self::assertResponseRedirects('/transaction/'. $fixture->getId() .'/edit');

        $fixture = $this->repository->findAll();

        self::assertSame('ETH', $fixture[0]->getCrypto());
        self::assertSame(1.0, $fixture[0]->getQuantity());
        self::assertSame(1320.0, $fixture[0]->getPrice());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Transaction();
        $fixture->setCrypto('BTC');
        $fixture->setQuantity(1);
        $fixture->setPrice( 22344);

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s/action/delete', $this->path, $fixture->getId()));
        $this->client->submitForm('SUPPRIMER');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/transaction/');
    }
}
