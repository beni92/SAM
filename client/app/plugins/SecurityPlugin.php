<?php
//dispatch
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
//acl
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclList;
//resources
use Phalcon\Acl\Resource;

class SecurityPlugin extends Plugin {

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        $auth = $this->session->get("auth");
        $config = $this->di->get("config");

        if(!$auth) {
            $role = $config->roles->guests;
        } else if($auth->role == 0){
            $role = $config->roles->employee;
        } else {
            $role = $config->roles->customer;
        }

        $controller	= $dispatcher->getControllerName();
        $action 	= $dispatcher->getActionName();

        $acl = $this->getAcl($config);

        $allowed = $acl->isAllowed($role, $controller, $action);

        if(!$allowed) {
            $dispatcher->forward(
                [
                    "controller" => "error",
                    "action"	 => "show401"
                ]);
        }
    }

    private function getAcl($config) {

        $acl = new AclList();

        $acl->setDefaultAction(Acl::DENY);
        $roles = [];

        foreach ($config->roles as $key => $value) {
            $roles[$key] = new Role($value);
            $acl->addRole($roles[$key]);
        }

        //create customer resources
        $customerRes = [
            "dashboard" => ["index"]
        ];

        foreach ($customerRes as $key => $value) {
            $acl->addResource(new Resource($key), $value);
        }

        //create employee resources
        $employeeRes = [

        ];

        foreach ($employeeRes as $key => $value) {
            $acl->addResource(new Resource($key), $value);
        }


        //create public resources
        $publicRes = [
            "index" => ["index", "login"],
            "error" => ["show401", "show404", "show500"]
        ];

        foreach ($publicRes as $key => $value) {
            $acl->addResource(new Resource($key), $value);
        }

        /*
         * iterate over all roles
         */
        foreach ($roles as $role) {
            /*
             * every role has access to the public resources
             */
            foreach ($publicRes as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow($role->getName(), $resource, $action);
                }
            }

            /*
             * customers and employees (everyone but not guests)
             * have access to the customerRes
             */
            if(!$role->getName() == $config->roles->guests) {
                foreach ($customerRes as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName, $resource, $action);
                    }
                }
            }

            /*
             * only employee have access to the employee resources
             */
            if($role->getName() == $config->roles->employee) {
                foreach ($employeeRes as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName, $resource, $action);
                    }
                }
            }
        }
        return $acl;
    }

}