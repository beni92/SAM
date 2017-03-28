<?php
namespace Sam\Server\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $pw = password_hash("sam101", PASSWORD_DEFAULT);

        return true;
    }
}

