<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        if (!isset($this->persistent->acl)) {
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);
            // Register roles
            $roles = [
                'guests' => new Role(
                    'Guests',
                    'No rights'
                ),
                'customers'  => new Role(
                    'Customers',
                    'Simple rights'
                ),
                'employee' => new Role(
                    'Employee',
                    'Extended rights'
                )
            ];
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            $customerResources = array();

            foreach ($customerResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            $employeeResources = array();

            foreach ($employeeResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Public area resources
            $publicResources = array(
                'index'      => array('index')
            );

            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action){
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }

                //Grants Access for customerResources to customers and employees
                if($role->getName() == 'Customers' || $role->getName() == 'Employee') {
                    foreach ($customerResources as $resource => $actions) {
                        foreach ($actions as $action){
                            $acl->allow($role->getName(), $resource, $action);
                        }
                    }
                }

                //Grants Access for employeeResources to employees only
                if($role->getName() == 'Employees') {
                    foreach ($employeeResources as $resource => $actions) {
                        foreach ($actions as $action){
                            $acl->allow($role->getName(), $resource, $action);
                        }
                    }
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }
        return $this->persistent->acl;
    }
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('auth');
        if($auth->role == 'Customers') {
            $role = 'Customers';
        } else if($auth->role == 'Employees') {
            $role = 'Employees';
        } else {
            $role = 'Guests';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $acl = $this->getAcl();
        if (!$acl->isResource($controller)) {
            /*$dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'show404'
            ]);*/
            return false;
        }
        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            /*$dispatcher->forward(array(
                'controller' => 'errors',
                'action'     => 'show401'
            ));*/
            $this->session->destroy();
            return false;
        }
    }
}