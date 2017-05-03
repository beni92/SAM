<?php
/**
 * Created by PhpStorm.
 * User: benpe
 * Date: 25.04.2017
 * Time: 12:05
 */

namespace Sam\Client\Models;

class Transaction
{
    /**
     * the id of the transaction
     * @var int
     */

    private $id;

    /**
     * the Stock which is bought
     * @var string
     */
    private $stockSymbol;

    /**
     * how many stocks where bought
     * @var double
     */
    private $shares;

    /**
     * how much one share cost
     * @var double
     */
    private $pricePerShare;

    /**
     * bought(0) or sold(1)
     * @var int
     */
    private $direction;

    /**
     * the customer who owns/owned the transacted shares
     * @var int
     */
    private $customerId;

    /**
     * the employee who made the transaction
     * @var int
     */
    private $employeeId;

    /**
     * the bank which is owner of the transaction
     * @var int
     */
    private $bankId;

    /**
     * the depot from customer
     * @var int
     */
    private $depotId;


    /**
     * the timestamp of the transaction
     * @var string
     */
    private $timestamp;

    /**
     * Transaction constructor.
     * @param int $id
     * @param string $stockSymbol
     * @param float $shares
     * @param float $pricePerShare
     * @param int $direction
     * @param int $customerId
     * @param int $employeeId
     * @param int $bankId
     * @param int $depotId
     * @param string $timestamp
     */
    public function __construct(
        $id,
        $stockSymbol,
        $shares,
        $pricePerShare,
        $direction,
        $customerId,
        $employeeId,
        $bankId,
        $depotId,
        $timestamp
    ) {
        $this->id = $id;
        $this->stockSymbol = $stockSymbol;
        $this->shares = $shares;
        $this->pricePerShare = $pricePerShare;
        $this->direction = $direction;
        $this->customerId = $customerId;
        $this->employeeId = $employeeId;
        $this->bankId = $bankId;
        $this->depotId = $depotId;
        $this->timestamp = $timestamp;
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
    public function getShares(): float
    {
        return $this->shares;
    }

    /**
     * @param float $shares
     */
    public function setShares(float $shares)
    {
        $this->shares = $shares;
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
    public function getDirection(): int
    {
        return $this->direction;
    }

    /**
     * @param int $direction
     */
    public function setDirection(int $direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     */
    public function setCustomerId(int $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    /**
     * @param int $employeeId
     */
    public function setEmployeeId(int $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return int
     */
    public function getBankId(): int
    {
        return $this->bankId;
    }

    /**
     * @param int $bankId
     */
    public function setBankId(int $bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @return int
     */
    public function getDepotId(): int
    {
        return $this->depotId;
    }

    /**
     * @param int $depotId
     */
    public function setDepotId(int $depotId)
    {
        $this->depotId = $depotId;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
