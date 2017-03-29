<?php
namespace Sam\Client\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function beforeExecuteRoute()
    {
        if(!$this->dispatcher->getPreviousActionName()){
            $this->init();
        }
    }

    private function init() {
        $this->tag->prependTitle(
            'SAM - '
        );

        $this->assets->addJs("libs/jquery.js");
        $this->assets->addCss("libs/nunito/nunito.css");
        $this->assets->addCss("libs/font-awesome-4.7.0/css/font-awesome.min.css");
    }


    protected function initialize() {

    }

    protected function preRequisits($forward = true) {
        $success = false;
        if($this->request->isPost()) {
            if($this->security->checkToken()) {
                $success = true;
            }
        }

        if($success === true) {
            return true;
        } else {
            if($forward === true) {
                $this->dispatcher->forward(
                    [
                        "controller" => "error",
                        "action"     => "401",
                    ]);
            }

            return false;
        }
    }

}
