<?php
namespace Sam\Client\Plugins;

use Phalcon\Mvc\User\Plugin;
use Sam\Client\Models\Bank;
use Sam\Client\Models\Depot;
use Sam\Client\Models\OwnedStock;
use Sam\Client\Models\Stock;
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

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
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

    public function login($loginName, $password)
    {
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
        if ($res === false) {
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
        $user->setAddress($res->address);

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
        if (!$newRes || $newRes === null || isset($newRes->error)) {
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

        if ($user->getRole() == $config->roles->customers) {
            return $this->loadCustomerInfo();
        } else {
            return $this->loadEmployeeInfo();
        }
    }

    public function loadEmployeeInfo()
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');


        if ($user->getRole() == $config->roles->employees) {
            $eRes = self::callAPI("GET", "employee/" . $user->getExtId(), $user->getLoginName(), $user->getPassword());

            $eRes = $this->stdClassFromJson($eRes);
            if ($eRes === false) {
                return false;
            }

            $transactions = $this->transactionsFromStdClass($eRes->return->transactions);
            $user->setTransactions($transactions);
            $this->session->set("auth", $user);
            return true;
        }
        return false;
    }

    public function loadCustomerInfo()
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');


        if ($user->getRole() == $config->roles->customers) {
            $cRes = self::callAPI("GET", "customer/" . $user->getLoginName(), $user->getLoginName(), $user->getPassword());

            $cRes = $this->stdClassFromJson($cRes);
            if ($cRes === false) {
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

        if ($user->getRole() === $config->roles->employees) {
            $cRes = self::callAPI("GET", "customer/", $user->getLoginName(), $user->getPassword());
            return $this->customersFromJson($cRes);
        }
        return false;
    }

    public function getCustomer($loginName)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("GET", "user/$loginName", $user->getLoginName(), $user->getPassword());
        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        $cRes = self::callAPI("GET", "customer/$loginName", $user->getLoginName(), $user->getPassword());
        $cRes = $this->stdClassFromJson($cRes);
        if ($cRes === false) {
            return false;
        }


        $cust = new \stdClass();
        $cust->user = $res;
        $cust->customer = $cRes->return->customer;
        $cust->depots = $cRes->return->depots;
        $loadedUser = $this->customerFromStdClass($cust);
        $loadedUser->setRole($config->roles->customers);


        foreach ($loadedUser->getDepots() as $depot) {
            $res = $this->getDepot($depot->getId(), $loadedUser->getLoginName());
            $depot->setValue($res->getValue());
        }

        return $loadedUser;
    }

    public function addCustomer($loginName, $password, $firstname, $lastname, $phone, $address)
    {
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
                "address" => $address,
                "role" => $config->roles->customers
            )
        );

        return $this->stdClassFromJson($res);
    }

    public function findCustomers($search)
    {
        if (!empty($search)) {
            $config = $this->di->get("config");
            /**
             * @var $user User
             */
            $user = $this->session->get('auth');

            if ($user->getRole() === $config->roles->employees) {
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
        if ($res === false) {
            return false;
        }

        $depot = $this->depotFromStdClass($res->depot);
        $depot->setUser($this->customerFromStdClass($res, false));
        $depot->setOwnedStocks($this->ownedStocksFromStdClass($res->ownedStocks, $depot));
        $depot->setValue($res->value);

        return $depot;
    }

    public function getStocks($stock)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("GET", "stock/both/$stock", $user->getLoginName(), $user->getPassword());
        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        return $this->stocksFromStdClass($res);
    }

    public function buyStock($shares, $symbol, $depot)
    {
        return $this->stockTransaction($shares, $symbol, $depot, 0);
    }

    public function sellStock($shares, $symbol, $depot, $owendStockId = false)
    {
        return $this->stockTransaction($shares, $symbol, $depot, 1, $owendStockId);
    }

    public function addDepot($loginName, $budget)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');
        $res = self::callAPI("POST", "depot", $user->getLoginName(), $user->getPassword(),
            array(
                "budget" => $budget,
                "loginName" => $loginName
            )
        );
        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        /** @var Depot $depot */
        $depot =  $this->depotFromStdClass($res->depot);
        $depot->setUser($this->getCustomer($loginName));
        $depot->setOwnedStocks(array());
        return $depot;
    }

    public function changeBudget($loginName, $value)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');
        $res = self::callAPI("POST", "customer", $user->getLoginName(), $user->getPassword(),
            array(
                "value" => $value,
                "loginName" => $loginName
            )
        );
        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        if (isset($res->success)) {
            return true;
        } else {
            return false;
        }
    }

    public function getBank()
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("GET", "bank", $user->getLoginName(), $user->getPassword());
        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        return $this->bankFromStdClass($res);
    }

    private function stdClassFromJson($cRes)
    {
        $cRes = json_decode($cRes);
        if (empty($cRes) || $cRes === null || isset($cRes->error)) {
            return false;
        } else {
            return $cRes;
        }
    }

    private function customersFromJson($cRes)
    {
        $cRes = $this->stdClassFromJson($cRes);
        if ($cRes === false) {
            return false;
        }
        $customers = array();
        foreach ($cRes->customers as $cust) {
            $customers[] = $this->customerFromStdClass($cust);
        }
        return $customers;
    }

    private function customerFromStdClass($cust, $getDepots = true)
    {
        $customer = new User();
        $customer->setLoginName($cust->user->loginNr);
        $customer->setBankId($cust->user->bankId);
        $customer->setFirstname($cust->user->firstname);
        $customer->setLastname($cust->user->lastname);
        $customer->setId($cust->user->id);
        $customer->setPhone($cust->user->phone);
        $customer->setBudget($cust->customer->budget);
        $customer->setExtId($cust->customer->id);
        $customer->setAddress($cust->user->address);
        if ($getDepots === true) {
            foreach ($cust->depots as $value) {
                $customer->addDepot($this->depotFromStdClass($value, $customer));
            }
        }
        return $customer;
    }

    private function depotFromStdClass($dep, $user = false)
    {
        $depot = new Depot();
        $depot->setId($dep->id);
        if (!empty($user)) {
            $depot->setUser($user);
        }

        $depot->setBudget($dep->budget);
        return $depot;
    }

    private function ownedStocksFromStdClass($ownedStocks, $depot)
    {
        $ret = array();
        foreach ($ownedStocks as $ownedStock) {
            $ownst = new OwnedStock(
                $ownedStock->id,
                $ownedStock->stockSymbol,
                $ownedStock->pricePerShare,
                $ownedStock->shares,
                $depot
            );
            $ret[] = $ownst;
        }
        return $ret;
    }

    private function stockFromStdClass($stock)
    {
        $retStock = new Stock(
            $stock->id,
            $stock->companyName,
            $stock->lastTradePrice,
            $stock->lastTradeTime,
            $stock->stockExchange,
            $stock->symbol,
            $stock->floatShares,
            $stock->marketCapitalization
        );
        return $retStock;
    }

    private function stocksFromStdClass($stocks)
    {
        $retStocks = array();
        foreach ($stocks as $stock) {
            $retStocks[] = $this->stockFromStdClass($stock);
        }
        return $retStocks;
    }

    private function stockTransaction($shares, $symbol, $depot, $direction, $ownedStockId = false)
    {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');

        $res = self::callAPI("POST", "stock", $user->getLoginName(), $user->getPassword(),
            array(
                "shares" => $shares,
                "direction" => $direction,
                "symbol" => $symbol,
                "depotId" => $depot,
                "ownedStockId" => $ownedStockId
            )
        );

        $res = $this->stdClassFromJson($res);
        if ($res === false) {
            return false;
        }

        $transaction = $this->transactionFromStdClass($res);
        return $transaction;
    }

    private function transactionsFromStdClass($cRes)
    {
        $transactions = array();
        /**
         * @var $transaction \stdClass
         */
        foreach ($cRes as $transaction) {
            $transactions[] = $this->transactionFromStdClass($transaction);
        }

        return $transactions;
    }

    private function transactionFromStdClass($transaction)
    {
        return new Transaction(
            $transaction->id,
            $transaction->stockSymbol,
            $transaction->shares,
            $transaction->pricePerShare,
            $transaction->direction,
            $transaction->customerId,
            $transaction->employeeId,
            $transaction->bankId,
            $transaction->depotId,
            $transaction->timestamp
        );
    }

    private function bankFromStdClass($res)
    {
        return new Bank($res->id, $res->name, $res->volume);
    }
}
