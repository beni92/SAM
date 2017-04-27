<?php
namespace Sam\Client\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 27.03.17
 * Time: 22:13
 */
class User
{
    /**
     * @var $id int
     */
    private $id;

    /**
     * @var $loginName string
     */
    private $loginName;

    /**
     * @var $password string
     */
    private $password;

    /**
     * @var $firstname string
     */
    private $firstname;

    /**
     * @var $lastname string
     */
    private $lastname;

    /**
     * @var $role string
     */
    private $role;

    /**
     * @var $budget int
     */
    private $budget;

    /**
     * @var $depots array
     */
    private $depots;

    /**
     * @var $bankId int
     */
    private $bankId;

    /**
     * @var $phone string
     */
    private $phone;

    /**
     * @var $extId int
     */
    private $extId;

    /**
     * @var $transactions array(Transaction)
     */
    private $transactions;

    public function __construct()
    {
        $this->setTransactions(array());
    }

    public function login($username, $password) {
        if(!empty($username) && !empty($password)) {
            $this->setLoginName($username);
            $this->setPassword($password);

        }
    }

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
     * @return string
     */
    public function getLoginName()
    {
        return $this->loginName;
    }

    /**
     * @param string $loginName
     */
    public function setLoginName($loginName)
    {
        $this->loginName = $loginName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param int $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return array
     */
    public function getDepots()
    {
        return $this->depots;
    }

    /**
     * @param $depot Depot
     */
    public function addDepot($depot)
    {
        $this->depots[$depot->getId()] = $depot;
    }

    /**
     * @return int
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param int $bankId
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return int
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * @param int $extId
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param array $transactions
     */
    public function setTransactions(array $transactions)
    {
        $this->transactions = $transactions;
    }



}