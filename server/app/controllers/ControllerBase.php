<?php
namespace Sam\Server\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize() {
        $this->view->disable();
    }
}
