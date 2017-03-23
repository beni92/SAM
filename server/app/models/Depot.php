<?php

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
     * This is a List of all the transactions made in this depot
     */
    private $transactions;

    /*
     * This is a list of all stocks in this depot
     */
    private $stocks;

    private $value;

    /*
     * This is the customer who owns this depot
     */
    private $owner;



}