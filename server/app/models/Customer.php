<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:09
 */
class Customer extends \Phalcon\Mvc\Model
{
    private $id;

    private $userId;

    private $depots;

    private $budget;

    private $createdByEmployeeId;


    public function initialize() {
        $this->setSource("Customer");

        $this->hasOne(
            "userId",
            "User",
            "id"
        );

        $this->hasMany(
            "depots",
            "Depot",
            "id"
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getDepots()
    {
        return $this->depots;
    }

    /**
     * @param mixed $depots
     */
    public function setDepots($depots)
    {
        $this->depots = $depots;
    }

    /**
     * @return mixed
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param mixed $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return mixed
     */
    public function getCreatedByEmployeeId()
    {
        return $this->createdByEmployeeId;
    }

    /**
     * @param mixed $createdByEmployeeId
     */
    public function setCreatedByEmployeeId($createdByEmployeeId)
    {
        $this->createdByEmployeeId = $createdByEmployeeId;
    }





}