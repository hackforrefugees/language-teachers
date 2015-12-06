<?php

namespace User\Acl;

/**
 * @uses Zend\Permissions\Acl\Acl as ZendAcl
 * @uses Zend\Permissions\Acl\Resource\GenericResource as Resource
 * @uses Zend\Permissions\Acl\Role\GenericRole as Role
 */

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * Class Acl
 * Loads the ACL defined in the config
 * @package User\Acl
 * @author Dominik Einkemmer
 */
class Acl extends ZendAcl
{
    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'anonymous';

    /**
     * Constructor
     * @param $config
     * @throws \Exception
     */
    public function  __construct($config)
    {
        if ($config == null) {
            throw new \Exception('No configuration present');
        }

        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources'])) {
            throw new \Exception('Invalid ACL-Config found');
        }

        $roles = $config['acl']['roles'];

        if (!isset($roles[self::DEFAULT_ROLE])) {
            $roles[self::DEFAULT_ROLE] = '';
        }

        $this->_addRoles($roles);
        $this->_addResources($config['acl']['resources']);
    }

    /**
     * Method to add roles to ACL
     * @param $roles
     * @return $this
     */
    protected function _addRoles($roles)
    {
        foreach ($roles as $name => $parent) {
            if (!$this->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }

                $this->addRole(new Role($name), $parent);
            }
        }
        return $this;
    }

    /**
     * Method to add resources to ACL
     * @param $resources
     * @return $this
     * @throws \Exception
     */
    protected function _addResources($resources)
    {
        foreach ($resources as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller == 'all') {
                    $controller = null;
                } else {
                    if (!$this->hasResource($controller)) {
                        $this->addResource(new Resource($controller));
                    }
                }

                foreach ($actions as $action => $role) {
                    if ($action == 'all') {
                        $action = null;
                    }

                    if ($permission == 'allow') {
                        $this->allow($role, $controller, $action);
                    } elseif ($permission == 'deny') {
                        $this->deny($role, $controller, $action);
                    } else {
                        throw new \Exception ('Invalid permissions defined: ' . $permission);
                    }
                }
            }
        }

        return $this;
    }

}