<?php
namespace Sam\Client\Plugins;

use Phalcon\Mvc\User\Plugin;
use Sam\Client\Models\Depot;
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
         */
        if($res) {
            $res = json_decode($res);
        } else {
            return false;
        }

        /*
         * check if error is set in the json object
         */
        if(isset($res->error)) {
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
        if(!$newRes || isset($newRes->error)) {
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

        return $this->loadCustomerInfo();
    }

    public function loadCustomerInfo() {
        $config = $this->di->get("config");
        /**
         * @var $user User
         */
        $user = $this->session->get('auth');


        if($user->getRole() == $config->roles->customers) {
            $cRes = self::callAPI("GET", "customer/" . $user->getExtId(), $user->getLoginName(), $user->getPassword());

            if (!$cRes || isset($cRes->error)) {
                return false;
            } else {
                $cRes = json_decode($cRes);
            }
            $user->setBudget($cRes->return[0]->budget);
            foreach ($cRes->return[1] as $value) {
                $depot = new Depot();
                $depot->setId($value->id);
                $depot->setBudget($value->budget);
                $user->addDepot($depot);
            }
            $this->session->set("auth", $user);
            return true;
        }
        return false;
    }

}