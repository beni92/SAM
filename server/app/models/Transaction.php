<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:15
 */
class Transaction extends \Phalcon\Mvc\Model
{
    /**
     * the id of the transaction
     * @var
     */

    private $id;

    /**
     * the Stock which is bought
     * @var
     */
    private $symbol;

    /**
     * how many stocks where bought
     * @var
     */
    private $shares;

    /**
     * how much one share cost
     * @var
     */
    private $pricePerShare;

    /**
     * bought(0) or sold(1)
     * @var
     */
    private $direction;

    /**
     * the customer who owns/owned the transacted shares
     * @var
     */
    private $customerId;

    /**
     * the employee who made the transaction
     * @var
     */
    private $employeeId;

    /**
     * the bank which is owner of the transaction
     * @var
     */
    private $bankId;

    /**
     * the depot from customer
     * @var
     */
    private $depotId;


    /**
     * the timestamp of the transaction
     * @var
     */
    private $timestamp;


    public function initialize()
    {
        $this->setSource("Transaction");

        $this->hasOne(
            "customerId",
            "Sam\\Server\\Models\\Customer",
            "id",
            array("alias" => "Customer")
        );

        $this->hasOne(
            "employeeId",
            "Sam\\Server\\Models\\Employee",
            "id",
            array("alias" => "Employee")
        );

        $this->hasOne(
            "stockSymbol",
            "Sam\\Server\\Models\\Stock",
            "symbol",
            array("alias" => "Stock")
        );

        $this->belongsTo(
            "bankId",
            "Sam\\Server\\Models\\Bank",
            "id",
            array("alias" => "Bank")
        );

        $this->belongsTo(
            "depotId",
            "Sam\\Server\\Models\\Depot",
            "id",
            array("alias" => "Depot")
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * @param mixed $shares
     */
    public function setShares($shares)
    {
        $this->shares = $shares;
    }

    /**
     * @return mixed
     */
    public function getPricePerShare()
    {
        return $this->pricePerShare;
    }

    /**
     * @param mixed $pricePerShare
     */
    public function setPricePerShare($pricePerShare)
    {
        $this->pricePerShare = $pricePerShare;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param mixed $bankId
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @return mixed
     */
    public function getDepotId()
    {
        return $this->depotId;
    }

    /**
     * @param mixed $depotId
     */
    public function setDepotId($depotId)
    {
        $this->depotId = $depotId;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param mixed $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
