<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:15
 */
class Transaction extends \Phalcon\Mvc\Model
{
    private $id;

    private $stock;

    private $amount;

    private $pricePerShare;

    /*
     * bought or sold
     */
    private $direction;

    /*
     * the customer who made the transaction
     */
    private $customer;

    /*
     * the employee who handled the transaction (optional)
     */
    private $employee;

    public function initialize() {


        $this->hasOne("employee");

        $this->hasOne("customer");

        $this->hasOne("stock");

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
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }




}