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
    /**
     * @var int
     */
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


}