<?php
namespace Sam\Client\Plugins;

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
use Sam\Client\Models\User;

class SecurityPlugin extends Plugin
{
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        /**
         * @var $auth User
         */
        $auth = $this->session->get("auth");
        $config = $this->di->get("config");
        //$this->session->destroy();
        if (!$auth) {
            $role = $config->roles->guests;
        } elseif ($auth->getRole() == $config->roles->employees) {
            $role = $config->roles->employees;
        } elseif ($auth->getRole() == $config->roles->customers) {
            $role = $config->roles->customers;
        } else {
            $role = $config->roles->guests;
        }

        $controller    = $dispatcher->getControllerName();
        $action    = $dispatcher->getActionName();

        $acl = $this->getAcl($config);

        if (!$acl->isResource($controller)) {
            $dispatcher->forward(
                [
                    "controller" => "error",
                    "action"     => "show404"
                ]
            );
            return;
        }



        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed) {
            $dispatcher->forward(
                [
                    "controller" => "error",
                    "action"     => "show401"
                ]
            );
            $this->session->destroy();
            return;
        }
    }

    private function getAcl($config)
    {
        $acl = new AclList();

        $acl->setDefaultAction(Acl::DENY);
        $roles = [];

        foreach ($config->roles as $key => $value) {
            $roles[$key] = new Role($value);
            $acl->addRole($roles[$key]);
        }

        //create customer resources
        $customerRes = [
            "dashboard" => ["index", "customer"],

        ];

        foreach ($customerRes as $key => $value) {
            $acl->addResource(new Resource($key), $value);
        }

        //create employee resources
        $employeeRes = [
            "dashboard" => ["index", "customer", "addCustomer", "bank"]
        ];

        foreach ($employeeRes as $key => $value) {
            $acl->addResource(new Resource($key), $value);
        }


        //create public resources
        $publicRes = [
            "index" => ["index", "login", "logout"],
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
            if ($role->getName() != $config->roles->guests) {
                foreach ($customerRes as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            /*
             * only employee have access to the employee resources
             */
            if ($role->getName() == $config->roles->employees) {
                foreach ($employeeRes as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }
        }
        return $acl;
    }
}
