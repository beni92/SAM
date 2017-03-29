<?php
namespace Sam\Client\Controllers;

use Sam\Client\Plugins\RestPlugin;

/**
 * Class IndexController
 *
 * This Controller is the login Controller. It is only called
 * while the login page is open
 */
class IndexController extends ControllerBase
{

    private function loadAssets() {
        /*$this->assets->addJs("js/validation.js");
        $this->assets->addJs("js/login.js");

        $this->assets->addCss("css/libs/slabo27px/slabo27px.css");
        $this->assets->addCss("css/login.css");*/
    }


    public function indexAction()
    {
        $this->loadAssets();
    }

    public function loginAction(){

        if($this->request->isPost() && $this->security->checkToken()) {
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");
            /**
             * @var $server RestPlugin
             */
            $server = $this->getDI()->get("server");
            if($server->login($username, $password) === true) {
                $this->dispatcher->forward(array(
                    "controller" => "dashboard",
                    "action" => "index"
                ));
            } else {
                $this->dispatcher->forward(array(
                    "controller" => "index",
                    "action" => "index"
                ));
            }
        }
    }

}

