<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\Customer;
use Sam\Server\Models\Depot;
use Sam\Server\Models\OwnedStock;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:46
 */
class CustomerController extends ControllerBase
{
    public function getAction($id) {
        $customer = Customer::findFirst(["id=:id:", 'bind' => ["id"=>$id]]);
        $depots = Depot::find(array("customerId = :id:", "bind" => array("id" => $customer->getId())));
        $ret = json_encode(array("return" => array($customer, $depots)));
        return $ret;
    }

    public function postAction() {

    }
}