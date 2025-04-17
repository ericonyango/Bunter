<?php

namespace App\Marketplace\Payment;

use App\Marketplace\Utility\BitcoinConverter;
use App\Marketplace\Utility\RPCWrapper;

class BitcoinPayment implements Coin
{
    protected $bitcoind;

    public function __construct()
    {
        $this->bitcoind = new RPCWrapper(config('coins.bitcoin.username'),
        config('coins.bitcoin.password'),
        config('coins.bitcoin.host'),
        config('coins.bitcoin.port'));
    }

    public function generateAddress(array $params = []): string
    {
        // only if the btc user is set then call with parameter

        if (array_key_exists('btc_user',$params))

            $address = $this->bitcoind->getnewaddress($params['btc_user']);
        else
            $address = $this->bitcoind-> getnewaddress();

        // Error in bitcoin

        if ($this->bitcoind->error)
            throw new \Exception($this->bitcoind->error);
        return $address;
    }

    public function getBalance(array $params = []): float
    {
        // first check by address

        if(array_key_exists('address', $params))
            $accountBalance = $this -> bitcoind -> getreceivedbyaddress($params['address'], (int)config('marketplace.bitcoin.minconfirmations'));
//        else if(array_key_exists('account', $params))
//            // fetch the balance of the account if this parameter is set
//            $accountBalance = $this -> bitcoind -> getbalance($params['account'], (int)config('marketplace.bitcoin.minconfirmations'));
        else
            throw new \Exception('You havent specified any parameter');

        if($this -> bitcoind -> error)
            throw new \Exception($this -> bitcoind -> error);

        return $accountBalance;
    }

    /**
     * @throws \Exception
     */
    public function sendToAddress(string $toAddress, float $amount): void
    {
        // call bitcoind procedure
        $this -> bitcoind -> sendtoaddress($toAddress, $amount);

        if($this -> bitcoind -> error)
            throw new \Exception("Sending to $toAddress amount $amount \n" . $this -> bitcoind -> error);


    }

    /**
     * @throws \Exception
     */
    public function sendToMany(array $addressesAmounts): void
    {
        // send to many addresses
//        foreach ($addressesAmounts as $address => $amount){
//            $this -> bitcoind -> sendtoaddress($address, $amount);
//        }

        $this->bitcoind->sendmany("", $addressesAmounts, (int)config('marketplace.bitcoin.minconfirmations'));


        if ($this->bitcoind->error) {
            $errorString = "";
            foreach ($addressesAmounts as $address => $amount){
                $errorString .= "To $address : $amount \n";
            }
            throw new \Exception( $this->bitcoind->error . "\nSending to: $errorString" );
        }
    }

    public function usdToCoin($usd): float
    {
        return round( BitcoinConverter::usdToBtc($usd), 8, PHP_ROUND_HALF_DOWN );
    }

    public function coinLabel(): string
    {
        return 'btc';
    }
}
