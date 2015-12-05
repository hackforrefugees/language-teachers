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
use Zend\Mvc\MvcEvent as MvcEvent;

/**
 * Class Authentication
 * Handles the authentication by an event
 * @package User\Event
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
     * preDispatch Event Handler
     * @param MvcEvent $event
     * @throws \Exception
     */
    public function preDispatch(MvcEvent $event)
    {
        $userAuth = $this->getUserAuthenticationPlugin();
        $acl = $this->getAclClass();
        $role = AclClass::DEFAULT_ROLE;

        if ($userAuth->hasIdentity()) {
            $user = $userAuth->getIdentity();
            $role = $user['userGroup'];
        }

        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        if (!$acl->hasResource($controller)) {
            throw new \Exception('Ressource ' . $controller . ' nicht definiert');
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            $url = $event->getRouter()->assemble(array(), array('name' => 'notAllowed'));
            $response = $event->getResponse();
            $response->getHeaders()->addHeaders(array(array('Location' => $url)));
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }

    /**
     * Sets authentication plugin
     * @param AuthPlugin $userAuthenticationPlugin
     * @return $this
     */
    public function setUserAuthenticationPlugin(AuthPlugin $userAuthenticationPlugin)
    {
        $this->_userAuth = $userAuthenticationPlugin;

        return $this;
    }

    /**
     * Gets the authentication plugin
     * @return null|AuthPlugin
     */
    public function getUserAuthenticationPlugin()
    {
        if ($this->_userAuth === null) {
            $this->_userAuth = new AuthPlugin();
        }

        return $this->_userAuth;
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