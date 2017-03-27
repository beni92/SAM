<?php

/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:46
 */
class CustomerController extends ControllerBase
{
    public function getAction($id) {
        $ret = json_encode(Customer::findFirst(["id=:id:", 'bind' => ["id"=>$id]]));
        return $ret;
    }

    public function postAction() {

    }
}