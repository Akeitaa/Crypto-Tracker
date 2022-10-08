<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Service\CoinMarketCapApi;
use App\Service\TotalAmountCalculator;
use App\Service\TransactionMetaDTOHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(TransactionRepository $transactionRepository,
                         TotalAmountCalculator $totalAmountCalculator,
                         CoinMarketCapApi $coinMarketCapApi,
                         TransactionMetaDTOHelper $transactionMetaDTOHelper): Response
    {
        //Get transactions from DB
        $transactions = $transactionRepository->findAll();

        //Get coins from CMC API
        $coinsFromCmcApi = $coinMarketCapApi->getCoinsList();

        //Get total amount from personal service calculator
        $totalAmount = $totalAmountCalculator->getTotalAmount($transactions,$coinsFromCmcApi);

        //Get Array DTO objects for view
        $transactionMetaDTOs = $transactionMetaDTOHelper->getData($transactions,$coinsFromCmcApi);

        return $this->render("home.html.twig",[
            'totalAmount' => $totalAmount,
            'transactionMetaDTOs' => $transactionMetaDTOs
        ]);
    }
}