<?php
namespace Sam\Client\Plugins;

use Phalcon\Mvc\User\Plugin;
use Sam\Client\Models\Depot;
use Sam\Client\Models\Transaction;
use Sam\Client\Models\User;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 28.03.17
 * Time: 11:32
 */
class RestPlugin extends Plugin
{

    private function callAPI($method, $urlExtend, $username, $password, $data = false)
    {
        $config = $this->getDI()->get("config");
        $url = $config->rest->url;
        $url .= "/".$urlExtend;

        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username.":".$password);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function login($loginName, $password) {
        /*
         * get the config
         */
        $config = $this->di->get("config");

        /*
         * gets a user by its login name
         */
        $res = self::callAPI("GET", "user/".$loginName, $loginName, $password);

        /*
         * if $res has a value transform the json of the answer to
         * an stdClass object
         *
         * check if error is set in the json object
         */
        $res = $this->stdClassFromJson($res);
        if($res === false) {
            return false;
        }

        /*
         * create a user
         */
        $user = new User();
        $user->setPassword($password);
        $user->setLoginName($loginName);
        $user->setBankId($res->bankId);
        $user->setFirstname($res->firstname);
        $user->setLastname($res->lastname);
        $user->setId($res->id);
        $user->setPhone($res->phone);

        /*
         * request to get the role of the user
         */
        $newRes = self::callAPI("GET", "user/".$loginName."/role", $loginName, $password);

        /*
         * if newres has no value or error is set
         * return false
         *
         * else decode the json to a stdclass object
         */
        if(!$newRes || $newRes === null || isset($newRes->error)) {
            return false;
        } else {
            $newRes = json_decode($newRes);
        }

        /*
         * sets the role of the user
         */
        $user->setRole($newRes->role);
        $user->setExtId($newRes->extId);
        /*
         * sets the authentication of the session
         */
        $this->session->set("auth", $user);

        if($user->getRole() == $config->roles->customers)
            return $this->loadCustomerInfo();
        else
            return $this->loadEmployeeInfo();
    }

    public function loadEmployeeInfo() {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');


        if($user->getRole() == $config->roles->employees) {
            $cRes = self::callAPI("GET", "employee/" . $user->getExtId(), $user->getLoginName(), $user->getPassword());

            $cRes = $this->stdClassFromJson($cRes);
            if($cRes === false) {
                return false;
            }

            $transactions = array();
            /**
             * @var $transaction \stdClass
             */
            foreach ($cRes->return->transactions as $transaction) {
                $intTrans = new Transaction(
                    $transaction->id,
                    $transaction->stockSymbol,
                    $transaction->shares,
                    $transaction->pricePerShare,
                    $transaction->direction,
                    $transaction->customerId,
                    $transaction->employeeId,
                    $transaction->bankId,
                    $transaction->depotId,
                    new \Datetime($transaction->timestamp));
                $transaction[] = $intTrans;
            }

            $user->setTransactions($transactions);
            $this->session->set("auth", $user);
            return true;
        }
        return false;
    }

    public function loadCustomerInfo() {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');


        if($user->getRole() == $config->roles->customers) {
            $cRes = self::callAPI("GET", "customer/" . $user->getLoginName(), $user->getLoginName(), $user->getPassword());

            $cRes = $this->stdClassFromJson($cRes);
            if($cRes === false) {
                return false;
            }
            $user->setBudget($cRes->return->customer->budget);
            foreach ($cRes->return->depots as $value) {
                $user->addDepot($this->depotFromStdClass($value, $user));
            }
            $this->session->set("auth", $user);
            return true;
        }
        return false;
    }

    public function getCustomers()
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        if($user->getRole() === $config->roles->employees) {
            $cRes = self::callAPI("GET", "customer/", $user->getLoginName(), $user->getPassword());
            return $this->customersFromJson($cRes);
        }
        return false;
    }

    public function getCustomer($loginName) {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("GET", "user/$loginName", $user->getLoginName(), $user->getPassword());
        $res = $this->stdClassFromJson($res);
        if($res === false) {
            return false;
        }

        $cRes = self::callAPI("GET", "customer/$loginName", $user->getLoginName(), $user->getPassword());
        $cRes = $this->stdClassFromJson($cRes);
        if($cRes === false) {
            return false;
        }


        $cust = new \stdClass();
        $cust->user = $res;
        $cust->customer = $cRes->return->customer;
        $cust->depots = $cRes->return->depots;
        $loadedUser = $this->customerFromStdClass($cust);
        $loadedUser->setRole($config->roles->customers);


        return $loadedUser;

    }

    public function addCustomer($loginName, $password, $firstname, $lastname, $phone) {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("POST", "user", $user->getLoginName(), $user->getPassword(),
            array(
                "loginName" => $loginName,
                "password" => $password,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "phone" => $phone,
                "role" => $config->roles->customers
            )
        );

        return $this->stdClassFromJson($res);
    }

    public function findCustomers($search)
    {
        if(!empty($search)) {
            $config = $this->di->get("config");
            /**
             * @var $user User
             */
            $user = $this->session->get('auth');

            if($user->getRole() === $config->roles->employees) {
                $cRes = self::callAPI("GET", "customer/$search/find", $user->getLoginName(), $user->getPassword());
                return $this->customersFromJson($cRes);
            } else {
                return false;
            }
        } else {
            return $this->getCustomers();
        }
    }

    public function getDepot($depotId, $loginName)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("GET", "depot/$loginName/$depotId", $user->getLoginName(), $user->getPassword());
        $res = $this->stdClassFromJson($res);
        if($res === false) {
            return false;
        }

        return $this->depotFromStdClass($res->depot);
    }

    private function stdClassFromJson($cRes) {
        if (empty($cRes) || $cRes === null || isset($cRes->error)) {
            return false;
        } else {
            return json_decode($cRes);
        }
    }

    private function customersFromJson($cRes) {
        $cRes = $this->stdClassFromJson($cRes);
        if($cRes === false) {
            return false;
        }
        $customers = array();
        foreach ($cRes->customers as $cust) {
            $customers[] = $this->customerFromStdClass($cust);
        }
        return $customers;
    }

    private function customerFromStdClass($cust) {
        $customer = new User();
        $customer->setLoginName($cust->user->loginNr);
        $customer->setBankId($cust->user->bankId);
        $customer->setFirstname($cust->user->firstname);
        $customer->setLastname($cust->user->lastname);
        $customer->setId($cust->user->id);
        $customer->setPhone($cust->user->phone);
        $customer->setBudget($cust->customer->budget);
        $customer->setExtId($cust->customer->id);
        foreach ($cust->depots as $value) {
            $customer->addDepot($this->depotFromStdClass($value, $customer));
        }
        return $customer;
    }

    private function depotFromStdClass($dep, $user = false) {
        $depot = new Depot();
        $depot->setId($dep->id);
        if(!empty($user)) {
            $depot->setUser($user);
        }

        $depot->setBudget($dep->budget);
        return $depot;
    }

    private function ownedStocksFromStdClass($ownedStocks) {

    }
}