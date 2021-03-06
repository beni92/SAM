<?php
namespace Sam\Server\Models;

use Sam\Server\Models\User;

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

    public function initialize()
    {
        $this->setSource("Customer");

        $this->hasOne(
            "userId",
            "Sam\\Server\\Models\\User",
            "id",
            array("alias" => "User")
        );

        $this->hasMany(
            "depots",
            "Sam\\Server\\Models\\Depot",
            "id",
            array("alias" => "Depots")
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
    public function getDepotList()
    {
        return $this->depots;
    }

    /**
     * @param mixed $depots
     */
    public function setDepotList($depots)
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

    public function changeBudget($difference)
    {
        if ($difference < 0 && $this->budget + $difference < 0) {
            return false;
        } else {
            $this->budget += $difference;
            if ($this->save() === false) {
                return false;
            }
            return true;
        }
    }
}
