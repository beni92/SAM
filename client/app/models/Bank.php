<?php
namespace Sam\Client\Models;
/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 28.03.17
 * Time: 10:16
 */
class Bank
{
    /** @var  int */
    private $id;

    /** @var  string */
    private $name;

    /** @var  double */
    private $volume;

    /**
     * Bank constructor.
     * @param $id
     * @param $name
     * @param $volume
     */
    public function __construct($id, $name, $volume)
    {
        $this->id = $id;
        $this->name = $name;
        $this->volume = $volume;
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


}