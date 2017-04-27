<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\Customer;
use Sam\Server\Models\Depot;
use Sam\Server\Models\OwnedStock;
use Sam\Server\Models\User;
use Sam\Server\Plugins\AuthenticationPlugin;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:46
 */
class CustomerController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    public function getAction($loginNr = false, $param = false)
    {

        $config = $this->di->get("config");
        $auth = $this->session->get("auth");
        if (!empty($loginNr) && empty($param)) {
            /**
             * @var $user User
             */
            $user = User::findFirst(array("loginNr = :id:", 'bind' => array("id" => $loginNr)));
            if(empty($user)){
                return json_encode(array("error"=>"Request error", "code" => "51"));
            }
            if(AuthenticationPlugin::isAllowedUser($user, $auth, $loginNr, $config)) {
                /**
                 * @var $customer Customer
                 */
                $customer = Customer::findFirst(array("userId = :id:", "bind" => array("id" => $user->getId())));
                $depots = Depot::find(array("customerId = :id:", "bind" => array("id" => $customer->getId())));
                $ret = json_encode(array("return" => array("customer" => $customer, "depots" => $depots)));
                return $ret;
            } else {
                return json_encode(array("error"=>"Request error", "code" => "52"));
            }
        } else if($auth["role"] === $config->roles->employees) {
            if(!empty($param) && $param === "find") {
                $users = User::find(array("bankId = :id: and (firstname like :q: or lastname like :q: or loginNr like :q:)", "bind" => array("id" => $auth["user"]->User->getBankId(), "q" => "%$loginNr%")));
            } else {
                $users = User::find(array("bankId = :id:", "bind" => array("id" => $auth["user"]->User->getBankId())));
            }

            $customers = array();
            /**
             * @var $user User
             */
            foreach ($users as $key => $user) {
                if($user->isCustomer()) {
                    $customers[] = Customer::findFirst(array("userId = :id:", 'bind' => array("id" => $user->getId())));
                }
            }

            $ret = array("customers" => array());
            /**
             * @var $customer Customer
             */
            foreach ($customers as $customer) {
                $user = User::findFirst(array("id = :id:", "bind" => array("id" => $customer->getUserId())));
                $depots = Depot::find(array("customerId = :id:", "bind" => array("id" => $customer->getId())));
                $ret["customers"][] = array("user" => $user, "customer" => $customer, "depots" => $depots);
            }
            return json_encode($ret);

        } else {
            return json_encode(array("error"=>"Request error", "code" => "53"));
        }
    }
    public function postAction() {

    }
}