<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 18.03.17
 * Time: 14:05
 */
class Role extends \Phalcon\Mvc\Model
{
    private $roleId;

    private $roleName;

    private $roleDescription;

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param mixed $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return mixed
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @param mixed $roleName
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;
    }

    /**
     * @return mixed
     */
    public function getRoleDescription()
    {
        return $this->roleDescription;
    }

    /**
     * @param mixed $roleDescription
     */
    public function setRoleDescription($roleDescription)
    {
        $this->roleDescription = $roleDescription;
    }


}