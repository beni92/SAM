<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:00
 */
class Stock extends \Phalcon\Mvc\Model
{
    private $companyName;

    private $lastTradePrice;

    private $lastTradeTime;

    private $stockExchange;

    private $symbol;

    private $marketCapitalization;

    private $floatShares;

    public function initialize() {
        $this->setSource("Stock");
    }

    public static function findBySymbol($symbol) {
        return Stock::findFirst(
            array(
                "symbol = :symbol:",
                "bind" => array(
                    "symbol" => $symbol
                )
            )
        );
    }

    public function createNew() {
        if($this->create === false) {

        } else {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getLastTradePrice()
    {
        return $this->lastTradePrice;
    }

    /**
     * @param mixed $lastTradePrice
     */
    public function setLastTradePrice($lastTradePrice)
    {
        $this->lastTradePrice = $lastTradePrice;
    }

    /**
     * @return mixed
     */
    public function getLastTradeTime()
    {
        return $this->lastTradeTime;
    }

    /**
     * @param mixed $lastTradeTime
     */
    public function setLastTradeTime($lastTradeTime)
    {
        $this->lastTradeTime = $lastTradeTime;
    }

    /**
     * @return mixed
     */
    public function getStockExchange()
    {
        return $this->stockExchange;
    }

    /**
     * @param mixed $stockExchange
     */
    public function setStockExchange($stockExchange)
    {
        $this->stockExchange = $stockExchange;
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param mixed $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return mixed
     */
    public function getMarketCapitalization()
    {
        return $this->marketCapitalization;
    }

    /**
     * @param mixed $marketCapitalization
     */
    public function setMarketCapitalization($marketCapitalization)
    {
        $this->marketCapitalization = $marketCapitalization;
    }

    /**
     * @return mixed
     */
    public function getFloatShares()
    {
        return $this->floatShares;
    }

    /**
     * @param mixed $floatShares
     */
    public function setFloatShares($floatShares)
    {
        $this->floatShares = $floatShares;
    }




}