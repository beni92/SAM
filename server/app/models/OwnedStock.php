<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:00
 */
class OwnedStock extends \Phalcon\Mvc\Model
{
    private $id;

    private $stockId;

    private $buyValue;

    private $amount;

    public function initialize(){
        $this->hasOne(
            "stockId",
            "Stock",
            "id"
        );
    }

}