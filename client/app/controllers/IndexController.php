<?php
namespace Sam\Client\Controllers;

use Sam\Client\Models\User;
use Sam\Client\Plugins\RestPlugin;

/**
 * Class IndexController
 *
 * This Controller is the login Controller. It is only called
 * while the login page is open
 */
class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    private function loadAssets()
    {
        /*$this->assets->addJs("js/validation.js");
        $this->assets->addJs("js/login.js");

        $this->assets->addCss("css/libs/slabo27px/slabo27px.css");
        $this->assets->addCss("css/login.css");*/
    }


    public function indexAction()
    {
        /** @var User $auth */
        $auth = $this->session->get("auth");
        if (!empty($auth)) {
            $this->dispatcher->forward(array(
                "controller" => "dashboard",
                "action" => "index"
            ));
        }
        $this->loadAssets();
    }

    public function loginAction()
    {

        /*
         * Destroys a previous session
         */
        if ($this->request->isPost() /*&& $this->security->checkToken(null, null, false)*/) {
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");
            /**
             * @var $server RestPlugin
             */
            $server = $this->di->get("server");

            if ($server->login($username, $password) === true) {
                $this->response->redirect("dashboard");
                /*$this->dispatcher->forward(array(
                    "controller" => "dashboard",
                    "action" => "index"
                ));*/
                return;
            } else {
                $this->response->redirect("");
                /*
                $this->dispatcher->forward(array(
                    "controller" => "index",
                    "action" => "index"
                ));*/
                return;
            }
        }

        $this->dispatcher->forward(array(
            "controller" => "error",
            "action" => "show401"
        ));
    }

    public function logoutAction()
    {
        $this->session->destroy(true);
        $this->response->redirect("");
        /*$this->dispatcher->forward(array(
            "controller" => "index",
            "action" => "index"
        ));*/
    }
}
