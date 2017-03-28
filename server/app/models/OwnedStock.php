<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:00
 */
class OwnedStock extends \Phalcon\Mvc\Model
{
    private $id;

    private $stockSymbol;

    private $pricePerShare;

    private $shares;

    private $depotId;

    public function initialize(){
        $this->setSource("OwnedStock");

        $this->hasOne(
            "stockSymbol",
            "Stock",
            "id"
        );

        $this->belongsTo(
            "depotId",
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
    public function getStockSymbol()
    {
        return $this->stockSymbol;
    }

    /**
     * @param mixed $stockSymbol
     */
    public function setStockSymbol($stockSymbol)
    {
        $this->stockSymbol = $stockSymbol;
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



}