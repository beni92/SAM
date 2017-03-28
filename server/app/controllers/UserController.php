<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\User;
use Sam\Server\Models\Customer;
use Sam\Server\Models\Employee;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:45
 */
class UserController extends ControllerBase
{
    public function getAction($loginNr) {
        $auth = $this->session->get("auth");
        $user = User::findFirst(array("loginNr = :lnr:", "bind" => array("lnr" => $loginNr)));

        if($user && $auth &&
            ($auth["role"] == "Customers" && $auth["user"]->user->getLoginNr() == $loginNr) ||
            ($auth["role"] == "Employees" && $auth["user"]->user->getBankId() ==  $user->getBankId())) {
            return json_encode($user);
        }
        else {
            if($auth === false || $auth["role"] != "Customers" || $auth["role"] != "Employees")
                return json_encode(array("error" => "not authenticated"));

            if($user === false ||
                ($auth["role"] == "Employees" && $auth["user"]->user->getBankId() !=  $user->getBankId()) ||
                ($auth["role"] == "Customers" && $auth["user"]->user->getLoginNr() != $loginNr)) {
                return json_encode(array("error" => "not authorised"));
            }

            return json_encode(array("error" => "internal error"));
        }
    }

    public function postAction() {

    }
}