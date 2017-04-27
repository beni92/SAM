<?php
namespace Sam\Client\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 28.03.17
 * Time: 10:16
 */
class Depot
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var OwnedStock
     */
    private $ownedStocks;

    /**
     * @var double
     */
    private $budget;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return OwnedStock
     */
    public function getOwnedStocks()
    {
        return $this->ownedStocks;
    }

    /**
     * @param OwnedStock $ownedStocks
     */
    public function setOwnedStocks($ownedStocks)
    {
        $this->ownedStocks = $ownedStocks;
    }

    /**
     * @return float
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param float $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


}