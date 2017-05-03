<?php
/**
 * Created by PhpStorm.
 * User: benpe
 * Date: 28.04.2017
 * Time: 10:26
 */

namespace Sam\Client\Models;

class Stock
{
    /** @var  int */
    private $id;

    /** @var  string */
    private $companyName;

    /** @var  double */
    private $lastTradePrice;

    /** @var string */
    private $lastTradeTime;

    /** @var  string */
    private $stockExchange;

    /** @var  string */
    private $symbol;

    /** @var  double */
    private $floatShares;

    /** @var double */
    private $marketCapitalization;

    /**
     * Stock constructor.
     * @param int $id
     * @param string $companyName
     * @param float $lastTradePrice
     * @param \DateTime $lastTradeTime
     * @param string $stockExchange
     * @param string $symbol
     * @param float $floatShares
     * @param float $marketCapitalization
     */
    public function __construct(
        $id,
        $companyName,
        $lastTradePrice,
        $lastTradeTime,
        $stockExchange,
        $symbol,
        $floatShares,
        $marketCapitalization
    ) {
        $this->id = $id;
        $this->companyName = $companyName;
        $this->lastTradePrice = $lastTradePrice;
        $this->lastTradeTime = $lastTradeTime;
        $this->stockExchange = $stockExchange;
        $this->symbol = $symbol;
        $this->floatShares = $floatShares;
        $this->marketCapitalization = $marketCapitalization;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return float
     */
    public function getLastTradePrice()
    {
        return $this->lastTradePrice;
    }

    /**
     * @param float $lastTradePrice
     */
    public function setLastTradePrice(float $lastTradePrice)
    {
        $this->lastTradePrice = $lastTradePrice;
    }

    /**
     * @return string
     */
    public function getLastTradeTime()
    {
        return $this->lastTradeTime;
    }

    /**
     * @param string $lastTradeTime
     */
    public function setLastTradeTime($lastTradeTime)
    {
        $this->lastTradeTime = $lastTradeTime;
    }

    /**
     * @return string
     */
    public function getStockExchange()
    {
        return $this->stockExchange;
    }

    /**
     * @param string $stockExchange
     */
    public function setStockExchange(string $stockExchange)
    {
        $this->stockExchange = $stockExchange;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return float
     */
    public function getFloatShares()
    {
        return $this->floatShares;
    }

    /**
     * @param float $floatShares
     */
    public function setFloatShares(float $floatShares)
    {
        $this->floatShares = $floatShares;
    }

    /**
     * @return float
     */
    public function getMarketCapitalization()
    {
        return $this->marketCapitalization;
    }

    /**
     * @param float $marketCapitalization
     */
    public function setMarketCapitalization(float $marketCapitalization)
    {
        $this->marketCapitalization = $marketCapitalization;
    }
}
