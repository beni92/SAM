<?php
namespace Sam\Server\Libraries;

use Phalcon\Mvc\Model\TransactionInterface;
use Sam\Server\Models\OwnedStock;
use Sam\Server\Models\Depot;
use Sam\Server\Models\Bank;
use Sam\Server\Models\Customer;
use Sam\Server\Models\Transaction;
use Sam\Server\Models\User;
use Sam\Server\Models\Stock;
use Sam\Server\Models\Employee;




use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 10:46
 */
class StockLibrary
{
    const USER = "bic4b17_04";
    const PASS = 'Jeingo4fi';
    const AGENT = "ABC";
    const WSDL = "http://edu.dedisys.org/ds-finance/ws/TradingService?wsdl";




    /**
     *
     *
     * @param $symbol string the symbol of the stock
     * @param $shares int how many shares the customer wants to buy
     * @param $depotId int the id of the depot in which the stock will be saved
     * @param $auth \stdClass the authentication of the session
     * @return bool|Transaction if an error happens false is returned else the transaction is returned
     */
    public static function buy($symbol, $shares, $depotId, $auth) {
        $depot = Depot::findFirst(array("id = :id:", "bind" => array("id" => $depotId)));
        /*
         * checks
         * if the owner of the depot is logged in
         * or
         * if an employee who is from the same bank as the customer is logged in
         */
        if($depot && (
                ($auth["role"] == "Customers" && $auth["user"]->getId() == $depot->getCustomerId()) ||
                ($auth["role"] == "Employees" && $auth["user"]->User->getBankId() == $depot->Customer->User->getBankId())
            )) {

            /**
             * get the bank of the authenticated user
             * @var $bank Bank
             */
            $bank = $auth["user"]->User->Bank;
            /**
             * gets the stock from the exchange
             * @var $stocks array
             */
            $stocks = self::getStocksBySymbols(array($symbol));

            /*
             * checks if the search for the stock was successful
             */
            if(!$stocks || count($stocks) != 1) {
                return false;
            }else {
                /**
                 * @var $stock Stock
                 */
                $stock = $stocks[0];
            }

            /*
             * calculates the price of the shares before buying them
             */
            $preCalculatedPrice = $stock->getLastTradePrice() * $shares;
            /*
             * checks if the depot has enough budget
             */
            if($depot->getBudget() - $preCalculatedPrice < 0) {
                return false;
            }

            /*
             * checks if the bank has enough budget
             */
            if($bank->getVolume() - $preCalculatedPrice < 0) {
                return false;
            }

            /*
             * buys the actual stock from the exchange
             */
            $boughtPrice = self::getSoapClient()->buy(array("symbol"=>$symbol, "shares"=>$shares))->return;

            if(!$boughtPrice) {
                return false;
            }

            /*
             * changes the budget of the depot from the customer
             */
            if($depot->changeBudget(-($boughtPrice * $shares)) === false) {
                /*
                 * sell the stocks if there was not enough budget
                 */
                self::resell($symbol, $shares, $boughtPrice, $depot);
                return false;
            }

            /*
             * changes the budget of the bank
             */
            if($bank->changeVolume(-($boughtPrice * $shares)) === false) {
                /*
                 * sell the stocks if there was not enough budget
                 */
                self::resell($symbol, $shares, $boughtPrice, $depot, false);
                return false;
            }
            /*
             * creates the stock for depot
             */
            $ownedStock = new OwnedStock();
            $ownedStock->setPricePerShare($boughtPrice);
            $ownedStock->setDepotId($depotId);
            $ownedStock->setShares($shares);
            $ownedStock->setStockSymbol($stock->getSymbol());

            /*
             * if something with the persisting is going right
             * sell these things again
             */
            if($ownedStock->save() === false) {
                /*
                 * sell the stocks if there is an error on save
                 * (should never happen)
                 */
                self::resell($symbol, $shares, $boughtPrice, $depot, false);
                return false;
            }

            /*
             * creates the transaction for the history of the bank
             *
             * ATTENTION: this is a transaction in cash flow not to the database
             */
            $transaction = new Transaction();
            $transaction->setStockSymbol($stock->getSymbol());
            $transaction->setShares($shares);
            $transaction->setDepotId($depotId);
            $transaction->setPricePerShare($boughtPrice);
            $transaction->setBankId($auth["user"]->User->getBankId());
            $transaction->setDirection(0);                          //direction for buy = 0
            $transaction->setUserId($auth["user"]->userId);
            /*
             * saves the transaction in the database
             */
            $transaction->save();
            return $transaction;
        } else {
            return false;
        }
    }

    /**
     * @param $name string a part of the name of the company to search in the stock exchange
     * @return array<Stock>
     */
    public static function getStockByCompanyName($name) {
        $res = self::getSoapClient()->findStockQuotesByCompanyName(array("partOfCompanyName"=>$name));
        if(isset($res->return)) {
            $stocks = self::quotesToStocks($res->return);
        } else {
            $stocks = array();
        }
        return $stocks;
    }

    public static function getStockHistoryBySymbol($symbol) {
        $res = self::getSoapClient()->getStockQuoteHistory(array("symbol" => $symbol));
        if(isset($res->return)) {
            return $res->return;
        } else {
            return array();
        }
    }

    /**
     * searches for the symbols given in the stock exchange.
     * @param $symbols array of symbols
     * @return array of stocks
     */
    public static function getStocksBySymbols($symbols) {
        $res = self::getSoapClient()->getStockQuotes(array("symbols" => $symbols));
        if(isset($res->return)) {
            $stocks = self::quotesToStocks($res->return);
        } else {
            $stocks = array();
        }
        return $stocks;
    }

    /**
     * sells a part of the ownedstock given
     * @param $ownedStock OwnedStock the stock of which some shares shall be sold
     * @param $shares int the amount of shares to sell
     * @param $auth \stdClass the authentication of the logged in user
     * @return bool|Transaction
     */
    public static function sell($ownedStock, $shares, $auth) {
        /**
         * @var $symbol string the symbol of the stock to sell
         */
        $symbol = $ownedStock->getStockSymbol();
        /**
         * @var $depot Depot
         */
        $depot = $ownedStock->Depot;
        /*
         * checks
         * if the owner of the depot is logged in
         * or
         * if an employee who is from the same bank as the customer is logged in
         */
        if($depot && (
                ($auth["role"] == "Customers" && $auth["user"]->getId() == $depot->getCustomerId()) ||
                ($auth["role"] == "Employees" && $auth["user"]->User->getBankId() == $depot->Customer->User->getBankId())
            )) {
            /**
             * @var $bank Bank
             */
            $bank = $auth->User->Bank;

            /*
             * if there are less shares in the owned stock then in the invoice
             * only sell the available amount
             */
            if($ownedStock->getShares() < $shares) {
                $shares = $ownedStock->getShares();
            }

            /*
             * sells the stock with the amount of shares to the stock exchange
             */
            $soldPrice = self::getSoapClient()->sell(array("symbol"=>$symbol, "shares"=>$shares));
            /*
             * changes the volume from the bank
             */
            $bank->changeVolume($soldPrice * $shares);
            /*
             * changes the budget of the depot
             */
            $depot->changeBudget($soldPrice * $shares);


            /*
             * creates the transaction for the history of the bank
             *
             * ATTENTION: this is a transaction in cash flow not to the database
             */
            $transaction = new Transaction();
            $transaction->setStockSymbol($symbol);
            $transaction->setShares($shares);
            $transaction->setDepotId($depot->getId());
            $transaction->setPricePerShare($soldPrice);
            $transaction->setBankId($bank->getId());
            $transaction->setDirection(1);                          //direction for sell = 1
            $transaction->setUserId($auth["user"]->getUserId());
            /*
             * saves the transaction in the database
             */
            $transaction->save();

            /*
             * reduces the amount of shares of the owned stock
             */
            $ownedStock->setShares($ownedStock->getShares() - $shares);
            /*
             * checks if there are no more shares left in the owned stock and deletes
             * it if true
             * else
             * the owned stock with the reduced amount of shares is saved
             */
            if($ownedStock->getShares() == 0) {
                $ownedStock->delete();
            } else {
                $ownedStock->save();
            }

            return $transaction;
        } else {
            return false;
        }
    }

    /**
     * creates a soap client and returns it
     * @return \SoapClient SoapClient
     */
    private static function getSoapClient() {
        $client = new \SoapClient(
            self::WSDL,
            [
                'login' => self::USER,
                'password' => self::PASS,
                'user_agent' => self::AGENT
            ]
        );
        return $client;
    }

    /**
     * @param $quotes \stdClass is a stdClass with at least the following parameters:
     * symbol, companyName, floatShares, lastTradePrice, marketCapitalization, stockExchange, lastTradeTime
     * @return array
     */
    private static function quotesToStocks($quotes) {
        $stocks = array();

        /*
         * creates a transaction
         * if for example a customer searches for a company called a
         * we get back like 1000 entries
         * if we save every entry at its own the search takes like 5seconds
         * if we make a transaction the result shows up in like 1 second
         *
         * Here we are in a static function
         * So we do not have the transaction manager from the application
         *
         * We have to make our own
         */
        $manager = new TxManager();

        /*
         * This way we get a new transaction manager
         */
        $dbTransaction = $manager->get();

        if($quotes) {
            if(is_array($quotes)) {
                foreach($quotes as $row) {
                    /*
                     * here we create the stock instance and save it in the database
                     */
                    $stocks[] = self::quoteToStock($row, $dbTransaction);
                }
            } else {
                /*
                 * here we create the stock instance and save it in the database
                 */
                $stocks[] = self::quoteToStock($quotes, $dbTransaction);
            }
        }

        /*
         * here we commit out transaction
         * before this is not run nothing is saved in the database
         */
        $dbTransaction->commit();

        return $stocks;
    }

    /**
     * creates a Stock and saves it in the db
     * @param $quote \stdClass the quote from the wsdl call
     * @param $dbTransaction TransactionInterface the open transaction in which this data set is saved to the db
     * @return Stock returns an instance of a Stock
     */
    private static function quoteToStock($quote, $dbTransaction) {
        /*
         * the Stock
         */
        $stock = new Stock();
        /*
         * ATTENTION: this is a database transaction
         * set the transaction here to save the data set as soon as the transaction
         * is committed
         */
        $stock->setTransaction($dbTransaction);
        /*
         * sets the symbol (not optional)
         */
        $stock->setSymbol($quote->symbol);
        /*
         * sets the companies name (not optional)
         */
        $stock->setCompanyName($quote->companyName);
        /*
         * sets the floatShares (if given)(optional)
         */
        if(isset($quote->floatShares))
            $stock->setFloatShares($quote->floatShares);
        /*
         * sets the lastTradePrice (if given)(optional)
         */
        if(isset($quote->lastTradePrice))
            $stock->setLastTradePrice($quote->lastTradePrice);
        /*
         * sets the marketCapitalization (if given)(optional)
         */
        if(isset($quote->marketCapitalization))
            $stock->setMarketCapitalization($quote->marketCapitalization);
        /*
         * sets the stock exchange(not optional)
         */
        $stock->setStockExchange($quote->stockExchange);
        /*
         * sets the floatShares (if given)(optional)
         */
        if(isset($quote->lastTradeTime))
            $stock->setLastTradeTime($quote->lastTradeTime);
        /*
         * save the stock to the db.
         * If an error occurs the transaction is rolled back
         * isn't actually saved here. It is not persisted until the commit of the
         * transaction is made
         */
        if($stock->save() === false) {
            $dbTransaction->rollback("Saving Stock failed");
        }
        return $stock;
    }

    /**
     * sells the already bought stocks back to the stock exchange
     * only use right after buying the stocks
     * @param $symbol string the symbol of the Stock
     * @param $shares int the amount of shares to resell
     * @param $boughtPrice double the price of one share
     * @param $depot Depot the depot of the customer
     */
    private static function resell($symbol, $shares, $boughtPrice, $depot, $difference = true) {
        $soldPrice = self::getSoapClient()->sell(array("symbol"=>$symbol, "shares"=>$shares));
        /*
         * @todo how should we handle the price difference
         */
        if($difference === true) {
            $depot->setBudget($depot->getBudget() + ($soldPrice * $shares - $boughtPrice * $shares));
            $depot->save();
        } else {
            $depot->changeBudget($soldPrice * $shares);
        }
    }

}

