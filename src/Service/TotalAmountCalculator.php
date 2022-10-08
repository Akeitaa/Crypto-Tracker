<?php

namespace App\Service;

class TotalAmountCalculator
{
    public function __construct(
        private CoinMarketCapApi $coinMarketCapApi
    ){}

    public function getTotalAmount(array $transactions,array $coinsFromCmcApi): float
    {
        $total = 0;

        foreach ($transactions as $transaction)
        {
            //Get crypto symbol
            $symbol = $transaction->getCrypto();
            //Get quantity bought
            $quantity = $transaction->getQuantity();
            //Get Id CMC
            $coinMarketCapCryptoId = $this->coinMarketCapApi->getIdCmcFromCryptoSymbol($symbol);
            //Get Value of 1 coin in Euros in CMC
            $valueOfOneUnityOfCrypto = $this->coinMarketCapApi->getCoinValueInEuro($coinMarketCapCryptoId,$coinsFromCmcApi);
            //Calcul amount
            $amountValue = round($quantity * $valueOfOneUnityOfCrypto,2);
            // Increment Total
            $total += $amountValue;
        }

        return $total;
    }

}