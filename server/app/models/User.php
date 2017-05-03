<?php
namespace Sam\Server\Models;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:00
 */
class User extends \Phalcon\Mvc\Model
{
    private $id;

    private $loginNr;

    private $firstname;

    private $lastname;

    private $password;

    private $phone;

    private $bankId;

    private $createdByEmployeeId;

    private $address;

    public function initialize()
    {
        $this->setSource("User");

        $this->hasOne(
            "bankId",
            "\\Sam\\Server\\Models\\Bank",
            "id",
            array("alias" => "Bank")
        );

        $this->hasOne(
            "createdByEmployeeId",
            "\\Sam\\Server\\Models\\Employee",
            "id",
            array("alias" => "createdBy")
        );
    }

    public function isEmployee()
    {
        $res = Employee::findFirst(array("userId = :id:", 'bind' => array("id" => $this->id)));
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function isCustomer()
    {
        $res = Customer::findFirst(array("userId = :id:", 'bind' => array("id" => $this->id)));
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
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
    public function getLoginNr()
    {
        return $this->loginNr;
    }

    /**
     * @param mixed $loginNr
     */
    public function setLoginNr($loginNr)
    {
        $this->loginNr = $loginNr;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param mixed $bankId
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @return mixed
     */
    public function getCreatedByEmployeeId()
    {
        return $this->createdByEmployeeId;
    }

    /**
     * @param mixed $createdByEmployeeId
     */
    public function setCreatedByEmployeeId($createdByEmployeeId)
    {
        $this->createdByEmployeeId = $createdByEmployeeId;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}
