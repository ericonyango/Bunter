<?php

namespace App\Marketplace\Payment;

use Illuminate\Support\Facades\Log;

class StubCoin
{
    function generateAddress(array $parameters = []) :string
    {
        return 'addressStub#' . rand(0,999999);
    }

    function getBalance(array $parameters = []) : float
    {
        return 101;
    }

    function sendFrom(string $fromAccount, string $toAddress, float $amount): void
    {
        Log::info("From accout " . $fromAccount . " to address " . $toAddress . " to amount " . $amount);
    }

    function sendToMany(array $addressesAmounts){

        foreach($addressesAmounts as $adr){
            Log::info("STB Transaction to address $adr");
        }
    }

    function usdToCoin( $usd ) : float {
        return $usd;
    }

    function coinLabel() : string {
        return 'stb';
    }

    function sendToAddress(string $toAddress, float $amount) {
        Log::info("Sending to address " . $toAddress . " to amount " . $amount);
    }
}
