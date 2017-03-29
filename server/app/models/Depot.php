<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 13:59
 */
class Depot extends \Phalcon\Mvc\Model
{
    private $id;

    /*
     * This is a list of all stocks in this depot
     */
    private $OwnedStocks;

    /*
     * This is the customer who owns this depot
     */
    private $customerId;

    /*
     * Each depot has a budget which can be set by the owner of the depot
     */
    private $budget;

    public function initialize() {
        $this->setSource("Depot");


        $this->hasOne(
            "customerId",
            "Sam\\Server\\Models\\Customer",
            "id",
            array("alias" => "Customer")

        );

        $this->hasMany(
            "stocks",
            "Sam\\Server\\Models\\OwnedStocks",
            "id",
            array("alias" => "OwnedStocks")
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
    public function getOwnedStocksList()
    {
        return $this->OwnedStocks;
    }

    /**
     * @param mixed $OwnedStocks
     */
    public function addOwnedStock($ownedStock)
    {
        $this->OwnedStocks[] = $ownedStock;
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

    public function changeBudget($difference) {
        if($difference < 0 && $this->budget + $difference < 0) {
            return false;
        } else {
            $this->budget += $difference;
            if($this->save() === false){
                return false;
            }
            return true;
        }
    }


}