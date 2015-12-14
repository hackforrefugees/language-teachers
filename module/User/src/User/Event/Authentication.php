<?php

/**
 * @namespace
 */

namespace User\Event;

/**
 * @uses User\Acl\Acl as AclClass
 * @uses User\Controller\Plugin\UserAuthentication as AuthPlugin
 * @uses Zend\Mvc\MvcEvent as MvcEvent
 */
use User\Acl\Acl as AclClass;
use User\Controller\Plugin\UserAuthentication as AuthPlugin;

/**
 * Class Authentication
 * Handles the authentication by an event
 * @package User\LanguageTeacherEvent
 * @author Dominik Einkemmer
 */
class Authentication
{

    /**
     * @var null
     */
    protected $_userAuth = null;

    /**
     * @var null
     */
    protected $_aclClass = null;
    /**
     * @var string
     */
    protected $config;

    /**
     * Constructor
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * preDispatch LanguageTeacherEvent Handler
     * @param string $controller
     * @param string $action
     * @param string $userGroup
     * @return boolean
     * @throws \Exception
     */
    public function checkPermission($controller, $action, $userGroup)
    {
        $acl = $this->getAclClass();

        $role = $userGroup;

        if (!$acl->hasResource($controller)) {
            throw new \Exception('Ressource ' . $controller . ' not defined');
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            return false;
        }
        return true;
    }

    /**
     * Sets the ACL class
     * @param AclClass $aclClass
     * @return $this
     */
    public function setAclClass(AclClass $aclClass)
    {
        $this->_aclClass = $aclClass;

        return $this;
    }

    /**
     * Gets the ACL class
     * @return null|AclClass
     */
    public function getAclClass()
    {
        if ($this->_aclClass === null) {
            $this->_aclClass = new AclClass($this->config);
        }

        return $this->_aclClass;
    }

}

?>