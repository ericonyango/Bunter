<?php

namespace App\Marketplace\Payment;

interface Coin
{
    public function generateAddress(array $parameters = []): string;

    public function getBalance(array $parameters = []): float;

    public function sendToAddress(string $toAddress, float $amount);

    public function sendToMany(array $addressesAmounts);

    public function usdToCoin($usd): float;

    public function coinLabel():string;
}
