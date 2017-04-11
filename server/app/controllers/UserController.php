<?php
namespace Sam\Server\Controllers;

use Sam\Server\Models\User;
use Sam\Server\Models\Customer;
use Sam\Server\Models\Employee;
use Sam\Server\Plugins\AuthenticationPlugin;

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:45
 */
class UserController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    public function getAction($loginNr, $param = false) {
        /**
         * gets the configuration object
         */
        $config = $this->getDI()->get('config');
        /*
         * gets the authenticated user
         */
        $auth = $this->session->get("auth");
        /*
         * get the requested user by its loginNr
         */
        $user = User::findFirst(array("loginNr = :lnr:", "bind" => array("lnr" => $loginNr)));

        /*
         * checks if the authenticated user is allowed to access the data
         */
        if(AuthenticationPlugin::isAllowedUser($user, $auth, $loginNr, $config) === true) {
            if($param) {
                switch ($param) {
                    /*
                     * if the param is role return the role of the user and as additional return value add the id
                     * of the either the customer or the employee (extId is meant as extended Id)
                     * If the user is an employee return the employee id else the customer id
                     */
                    case "role":
                        return json_encode(array("role"=>$auth["role"], "extId" =>  $auth['user']->getId()));
                        break;
                    /*
                     * if none of the switch arguments fitted a wrong parameter is requested
                     * we return an error here
                     */
                    default:
                        return json_encode(array("error"=>"wrong parameter"));
                        break;
                }
            } else {
                return json_encode($user);
            }
        }
        else {
            /*
             * if the user is not authenticated return the an error message with not authenticated
             */
            if($auth === false || $auth["role"] != $config->roles->customers || $auth["role"] != $config->roles->employees)
                return json_encode(array("error" => "not authenticated"));
            /*
             * checks if the problem is that the user is not allowed to access the data
             */
            if(AuthenticationPlugin::isAllowedUser($user, $auth, $loginNr, $config) === false) {
                return json_encode(array("error" => "not authorised"));
            }

            /*
             * if non of the above fitted a weired unexpected internal error occurred
             */
            return json_encode(array("error" => "internal error"));
        }
    }


    public function postAction() {
        /**
         * gets the configuration object
         */
        $config = $this->getDI()->get('config');
        /*
         * gets the authenticated user
         */
        $auth = $this->session->get("auth");


        if(AuthenticationPlugin::isAllowedEmployee($auth, $auth['user']->user->getBankId(), $config) === true) {

            $loginNr = $this->request->getPost("loginName");
            $firstname = $this->request->getPost("firstname");
            $lastname = $this->request->getPost("lastname");
            $phone = $this->request->getPost("phone");
            $role = $this->request->getPost("role");
            $password = $this->request->getPost("password");
            $createdBy = $auth['user']->getId();


            $this->db->begin();

            $user = new User();
            $user->setBankId($auth['user']->user->getBankId());
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPhone($phone);
            $user->setLoginNr($loginNr);
            $user->setCreatedByEmployeeId($createdBy);

            if($user->save() === false) {
                $this->db->rollback();
                return json_encode(array("error" => "user could not be created", "code" => 00));
            }

            if($role == $config->roles->customers) {
                $customer = new Customer();
                $customer->setUserId($user->getId());
                $customer->setBudget(0);
                if($customer->save() === false) {
                    $this->db->rollback();
                    return json_encode(array("error" => "user could not be created", "code" => 01));
                }
            } else if($role == $config->roles->employees) {
                $employee = new Employee();
                $employee->setUserId($user->getId());
                if($employee->save() === false) {
                    $this->db->rollback();
                    return json_encode(array("error" => "user could not be created", "code" => 02));
                }
            } else {
                $this->db->rollback();
                return json_encode(array("error" => "role error", "code" => 04));
            }

            $this->db->commit();

        } else {
            return json_encode(array("error" => "only employees allowed to create user", "code" => 05));
        }

    }
}