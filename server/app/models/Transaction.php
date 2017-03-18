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


}