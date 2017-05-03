<?php
namespace Sam\Server\Plugins;

use Sam\Server\Models\User;
use Sam\Server\Models\Employee;
use Sam\Server\Models\Customer;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 23.03.17
 * Time: 18:26
 */
class AuthenticationPlugin extends \Phalcon\Mvc\User\Plugin
{
    /**
     *
     * this method is used to authenticate the user and return the authenticated user
     * @param $basicAuth array the basic authentication header from the request
     * @return bool|array returns false when user not found or not authenticated if user authenticated returns the user
     */
    private function authenticateUser($basicAuth=[])
    {
        $config = $this->getDI()->get('config');

        $user = User::findFirst([
            'loginNr = :loginNr:',
            'bind' => ["loginNr" => "".$basicAuth['username'].""]]
        );

        if ($user) {
            if (password_verify($basicAuth['password'], $user->getPassword()) === true) {
                $employee = Employee::findFirst([
                        'userId = :id:',
                        'bind' => ["id" => $user->getId()]]
                );
                if ($employee) {
                    return array("user" => $employee, "role" => $config->roles->employees);
                }

                $customer = Customer::findFirst([
                        'userId = :id:',
                        'bind' => ["id" => $user->getId()]]
                );
                if ($customer) {
                    return array("user" => $customer, "role" => $config->roles->customers);
                }
            }
        }

        return array("user" => false, "role" => $config->roles->guests);
    }

    /**
     * Use this if a customer as well as an employee may use sth
     *
     * @param $user User
     * @param $auth \stdClass
     * @param $loginNr int
     * @return bool if the user is allowed to access data true is returned else false
     */
    public static function isAllowedUser($user, $auth, $loginNr, $config, $bankId = false)
    {
        if (!empty($auth) && (($auth["role"] == $config->roles->customers && $auth["user"]->user->getLoginNr() == $loginNr) ||
                (!empty($user) && self::isAllowedEmployee($auth, $user->getBankId(), $config)) ||
                (!empty($bankId) && self::isAllowedEmployee($auth, $bankId, $config)))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * use this if only an employee may use sth
     *
     * @param $auth \stdClass
     * @param $bankId int
     * @return bool returns true if an employee is authorised to do sth
     */
    public static function isAllowedEmployee($auth, $bankId, $config)
    {
        if ($auth["role"] == $config->roles->employees && $auth["user"]->user->getBankId() ==  $bankId) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * runs as first in the loop.
     * If a any request is made an authentication has to be made
     * this method checks the basic Auth header from the request and creates in the session
     * the auth header
     *
     * If a user is authenticated in the session auth field a user object is added as well as field
     * with the role of the object (either Guests, Customers or Employees)
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     */
    public function beforeDispatch(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $auth = $this->request->getBasicAuth();
        $ret = $this->authenticateUser($auth);
        $this->session->set("auth", $ret);
    }
}
