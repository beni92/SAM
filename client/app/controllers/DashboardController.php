<?php
/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 29.03.17
 * Time: 12:26
 */

namespace Sam\Client\Controllers;


class DashboardController extends ControllerBase
{
    public function indexAction() {
        $this->view->user = $this->session->get("auth");
    }
}