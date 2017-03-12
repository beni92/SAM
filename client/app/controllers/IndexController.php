<?php

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

    public function loginAction() {
        if($this->preRequisits($forward = false)) {
            $username = $this->request->getPost("username", ["trim", "string"]);
            $password = $this->request->getPost("password");

            $passed = true;
            $errorMessages = [];

            if(!empty($username)) {
    			if($this->request->getPost("username") != $username) {
                    $passed = false;
                    $errorMessages["username"] = "Username has wrong format!";
                }else {
                    if(strlen($username) > 255) {
                        $passed = false;
                        $errorMessages["username"] = "Username is too long!";
                    } else {
                        if(strlen(trim($username)) < 3) {
                            $passed = false;
                            $errorMessages["username"] = "Please enter a username which consists of at least 3 characters!";
                        }
                    }
                }
    		}

            if(!empty($password)) {
                if(strlen($password) < 6) {
                    $passed = false;
                    $errorMessages["password"] = "Your password must be at least 6 characters long!";
                }
            }

            if($passed === true && count($errorMessages) == 0) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $res = User::login($username, $password);
                if($res === true) {

                } else {
                    $this->view->message = $res;
                }
            } else {
                $this->view->message = $errorMessages;
            }


        }
    }
}

