<?php
namespace Sam\Client\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 27.03.17
 * Time: 22:14
 */
class OwnedStock
{
    /** @var  int */
    private $id;

    /**
     * @var string
     */
    private $stockSymbol;

    /**
     * @var double
     */
    private $pricePerShare;

    /**
     * @var int
     */
    private $shares;

    /**
     * @var Depot
     */
    private $depot;

    /**
     * OwnedStock constructor.
     * @param string $stockSymbol
     * @param float $pricePerShare
     * @param int $shares
     * @param Depot $depot
     */
    public function __construct($id, $stockSymbol, $pricePerShare, $shares, Depot $depot)
    {
        $this->id = $id;
        $this->stockSymbol = $stockSymbol;
        $this->pricePerShare = $pricePerShare;
        $this->shares = $shares;
        $this->depot = $depot;
    }


    /**
     * @return string
     */
    public function getStockSymbol(): string
    {
        return $this->stockSymbol;
    }

    /**
     * @param string $stockSymbol
     */
    public function setStockSymbol(string $stockSymbol)
    {
        $this->stockSymbol = $stockSymbol;
    }

    /**
     * @return float
     */
    public function getPricePerShare(): float
    {
        return $this->pricePerShare;
    }

    /**
     * @param float $pricePerShare
     */
    public function setPricePerShare(float $pricePerShare)
    {
        $this->pricePerShare = $pricePerShare;
    }

    /**
     * @return int
     */
    public function getShares(): int
    {
        return $this->shares;
    }

    /**
     * @param int $shares
     */
    public function setShares(int $shares)
    {
        $this->shares = $shares;
    }

    /**
     * @return Depot
     */
    public function getDepot(): Depot
    {
        return $this->depot;
    }

    /**
     * @param Depot $depot
     */
    public function setDepot(Depot $depot)
    {
        $this->depot = $depot;
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


}