<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\Employee;
use Sam\Server\Models\Transaction;
use Sam\Server\Plugins\AuthenticationPlugin;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:46
 */
class EmployeeController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    public function getAction($id) {
        /**
         * gets the last few transactions
         */
        $auth = $this->session->get("auth");
        $config = $this->di->get("config");
        $bankId = $auth["user"]->User->getBankId();
        if(AuthenticationPlugin::isAllowedEmployee($auth, $bankId, $config)) {
            $employee = Employee::findFirst(array("id = :id:",  "bind" => array("id" => $id)));
            $transactions = Transaction::find(array("employeeId = :id:", "order" => "timestamp", "limit" => "10", "bind" => array("id" => $id)));
            return json_encode(array("employee" => $employee, "transactions" => $transactions));
        } else {
            return json_encode(array("error" => "not authorised", "code" => "101"));
        }
    }

}