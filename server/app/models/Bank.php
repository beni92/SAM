<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:01
 */
class Bank extends \Phalcon\Mvc\Model
{

    private $id;

    private $name;

    private $volume;

    /*
     * This is a List of all the transactions made in this depot
     */
    private $transactions;

    public function initialize() {
        $this->setSource("Bank");

        $this->hasMany(
            "transactions",
            "Sam\\Server\\Models\\Transaction",
            "id",
            array("alias" => "Transaction")
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    public function changeVolume($difference) {
        if($difference < 0 && $this->volume + $difference < 0) {
            return false;
        } else {
            $this->volume += $difference;
            if($this->save() === false) {
                return false;
            }
            return true;
        }
    }

}