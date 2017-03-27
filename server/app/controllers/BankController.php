<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:45
 */
class BankController extends ControllerBase
{

    public function getAction($id) {

        $auth = $this->session->get("auth");
        $bid = $auth["user"]->user->getBankId();
        $ret =  json_encode(Bank::findFirst(array("id = :id:", "bind" => array("id" => $id))));
        return $ret;
    }

    public function postAction() {

    }
}