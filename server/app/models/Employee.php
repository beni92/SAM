<?php
namespace Sam\Server\Models;

use Sam\Server\Models\User;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:10
 */
class Employee extends \Phalcon\Mvc\Model
{
    private $id;

    private $userId;

    public function initialize()
    {
        $this->setSource("Employee");

        $this->hasOne(
            "userId",
            "Sam\\Server\\Models\\User",
            "id",
            array("alias" => "User")
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
