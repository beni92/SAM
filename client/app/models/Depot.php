<?php
namespace Sam\Client\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 28.03.17
 * Time: 10:16
 */
class Depot
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array(OwnedStock)
     */
    private $ownedStocks;

    /**
     * @var double
     */
    private $budget;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array(OwnedStock)
     */
    public function getOwnedStocks($symbol = false)
    {
        if($symbol === false) {
            return $this->ownedStocks;
        } else {
            $retArr = array();
            /** @var OwnedStock $ownedStock */
            foreach ($this->ownedStocks as $ownedStock) {
                if($ownedStock->getStockSymbol() === $symbol) {
                    $retArr[] = $ownedStock;
                }
            }
            return $retArr;
        }
    }

    /**
     * @param array(OwnedStock) $ownedStocks
     */
    public function setOwnedStocks($ownedStocks)
    {
        $this->ownedStocks = $ownedStocks;
    }

    /**
     * @param $symbol string the symbol to get all shares owned
     * @return int returns all summed up shares from owned stocks with the given symbol
     */
    public function getOwnedStocksShares($symbol) {
        $ret = 0;
        /** @var OwnedStock $ownedStock */
        foreach ($this->ownedStocks as $ownedStock) {
            if($ownedStock->getStockSymbol() === $symbol) {
                $ret += $ownedStock->getShares();
            }
        }
        return $ret;
    }

    /**
     * @return float
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param float $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


}