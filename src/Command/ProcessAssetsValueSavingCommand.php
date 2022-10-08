<?php

namespace App\Command;

use App\Entity\DailyValuation;
use App\Repository\TransactionRepository;
use App\Service\CoinMarketCapApi;
use App\Service\TotalAmountCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProcessAssetsValueSavingCommand extends Command
{
    protected static $defaultName = "app:save-value-assets";

    public function __construct(
        private EntityManagerInterface $em,
        private TotalAmountCalculator $totalAmountCalculator,
        private CoinMarketCapApi $coinMarketCapApi,
        private TransactionRepository $transactionRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Save Value Assets');
        $this->setHelp(
            'This command process assets value saving'
        );
        $this->addArgument('date', InputArgument::OPTIONAL, 'Saving date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if($input->getArgument('date') !== null)
        {
            $date = new \DateTimeImmutable($input->getArgument('date'));
        }
        else
        {
            $date = new \DateTimeImmutable();
        }

        //Get transactions from DB
        $transactions = $this->transactionRepository->findAll();

        //Get coins from CMC API
        $coinsFromCmcApi = $this->coinMarketCapApi->getCoinsList();

        //Get total amount from personal service calculator
        $totalAmount = $this->totalAmountCalculator->getTotalAmount($transactions,$coinsFromCmcApi);

        //Save in BDD
        $dailyValuation = new DailyValuation();
        $dailyValuation->setCreatedAt($date);
        $dailyValuation->setAmount($totalAmount);
        $this->em->persist($dailyValuation);
        $this->em->flush();

        $io = new SymfonyStyle($input, $output);

        $io->success('La commande a bien été traitée.');

        return Command::SUCCESS;
    }
}