<?php

namespace App\Service;

use App\DTO\TransactionMetaDTO;
use App\Entity\Transaction;

class TransactionMetaDTOHelper
{
    public function __construct(
        private CoinMarketCapApi $coinMarketCapApi
    ){}

    public function getData(array $transactions,array $coinsFromCmcApi): array
    {
        $data = [];

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction)
        {
            //Get crypto symbol
            $symbol = $transaction->getCrypto();
            //Get quantity bought
            $quantity = $transaction->getQuantity();
            //Get Id CMC
            $coinMarketCapCryptoId = $this->coinMarketCapApi->getIdCmcFromCryptoSymbol($symbol);
            //Get Metadata
            $cryptoMetadata = $this->coinMarketCapApi->getCryptoMetadata($coinMarketCapCryptoId);
            //Get Value of 1 coin in Euros in CMC
            $valueOfOneUnityOfCrypto = $this->coinMarketCapApi->getCoinValueInEuro($coinMarketCapCryptoId,$coinsFromCmcApi);
            //Calcul amount
            $amountValueToday = round($quantity * $valueOfOneUnityOfCrypto,2);
            //Is amount value superior or inferior to the price that user bought it
            $isInvestmentProfitable = $this->isInvestmentProfitable($transaction->getPrice(),$amountValueToday);
            if($cryptoMetadata !== null)
            {
                $transactionMetaDTO = new TransactionMetaDTO(
                    $cryptoMetadata['logo'],$cryptoMetadata['name'],$cryptoMetadata['symbol'],$isInvestmentProfitable
                );

                $data[] = $transactionMetaDTO;
            }
        }

        return $data;
    }

    private function isInvestmentProfitable(float $amountInvest,float $amountValueToday): bool
    {
        if($amountInvest <= $amountValueToday)
        {
            return true;
        }
        return false;
    }
}