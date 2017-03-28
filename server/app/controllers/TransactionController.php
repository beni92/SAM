<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\Transaction;
use Sam\Server\Models\Customer;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:47
 */
class TransactionController extends ControllerBase
{
    public function getAction($id) {
        return json_encode(Transaction::findFirst(array("id = :id:", "bind" => array("id" => $id))));
    }

    public function getParamAction($param, $amount, $id) {
        if($param == "customer") {
            $auth = $this->session->get("auth");
            $customer = Customer::findFirst(array("id = :id:", "bind" => array("id" => $id)));

            if(($auth["role"] == "Employees" && $auth["user"]->user->bankId == $customer->user->bankId) || $customer->getId() == $auth["user"]->getId()) {
                return json_encode(Transaction::find(array("userId = :id:",  "limit" => $amount, "bind" => array("id", $customer->getId()))));
            }
        }
    }

    public function postAction() {

    }
}