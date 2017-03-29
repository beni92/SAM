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
    public function getAction($loginNr, $param = false) {
        $auth = $this->session->get("auth");
        $user = User::findFirst(array("loginNr = :lnr:", "bind" => array("lnr" => $loginNr)));


        if($user && $auth &&
            ($auth["role"] == "Customers" && $auth["user"]->user->getLoginNr() == $loginNr) ||
            ($auth["role"] == "Employees" && $auth["user"]->user->getBankId() ==  $user->getBankId())) {
            if($param) {
                switch ($param) {
                    case "role":
                        $retId = $auth['role'] == "Customers" ? "customerId" : "employeeId";
                        return json_encode(array("role"=>$auth["role"], $retId =>  $auth['user']->getId()));
                        break;
                    default:
                        return json_encode(array("error"=>"wrong parameter"));
                        break;
                }
            } else {
                return json_encode($user);
            }
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