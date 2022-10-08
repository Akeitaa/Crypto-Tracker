<?php

namespace App\DTO;

class TransactionMetaDTO
{
    public function __construct(
        public string $logoUrl,
        public string $cryptoName,
        public string $cryptoSymbol,
        public bool $isInvestmentProfitable
    ){}
}