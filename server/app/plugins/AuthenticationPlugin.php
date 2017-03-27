<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 23.03.17
 * Time: 18:26
 */
class AuthenticationPlugin extends \Phalcon\Mvc\User\Plugin
{
    /**
     *
     * this method is used to authenticate the user and return the authenticated user
     * @param $basicAuth array the basic authentication header from the request
     * @return bool|static returns false when user not found or not authenticated if user authenticated returns the user
     */
    private function authenticateUser($basicAuth=[]) {

        $user = User::findFirst([
            'loginNr = :loginNr:',
            'bind' => ["loginNr" => "".$basicAuth['username'].""]]
        );

        if($user) {
            if(password_verify($basicAuth['password'], $user->getPassword()) === true) {
                $employee = Employee::findFirst([
                        'userId = :id:',
                        'bind' => ["id" => $user->getId()]]
                );
                if($employee) {
                    return array("user" => $employee, "role" => "Employees");
                }

                $customer = Customer::findFirst([
                        'userId = :id:',
                        'bind' => ["id" => $user->getId()]]
                );
                if($customer) {
                    return array("user" => $customer, "role" => "Customers");
                }

            }
        }

        return array("user" => false, "role" => "Guests");
    }

    public function beforeDispatch(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher) {
        $auth = $this->request->getBasicAuth();
        $ret = $this->authenticateUser($auth);
        $this->session->set("auth", $ret);
    }
}