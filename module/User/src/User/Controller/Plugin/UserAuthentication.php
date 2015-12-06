<?php

namespace User\Controller\Plugin;

/**
 * @uses Zend\Mvc\Controller\Plugin\AbstractPlugin
 * @uses Zend\Authentication\AuthenticationService
 * @uses Zend\Authentication\Adapter\DbTable
 */
use BitDbBcryptAuthAdapter\AuthAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class UserAuthentication
 * Handles Auth Adapter and Auth Service to check the identity of a logged in user
 * @package User\Controller\Plugin
 * @author Dominik Einkemmer
 */
class UserAuthentication extends AbstractPlugin
{

    /**
     * @var AuthAdapter
     */
    protected $_authAdapter = null;

    /**
     * @var AuthenticationService
     */
    protected $_authService = null;

    /**
     * Check if identity is present
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Return current identity of logged in user
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Sets Auth Adapter
     * @param AuthAdapter $authAdapter
     * @return $this
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->_authAdapter = $authAdapter;
        return $this;
    }

    /**
     * Returns Auth Adapter
     * @return AuthAdapter|null
     */
    public function getAuthAdapter()
    {
        if ($this->_authAdapter === null) {
            $this->setAuthAdapter(new AuthAdapter());
        }

        return $this->_authAdapter;
    }

    /**
     * Sets Auth Service
     * @param AuthenticationService $authService
     * @return $this
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->_authService = $authService;

        return $this;
    }

    /**
     * Get Auth Service
     * @return null|AuthenticationService
     */
    public function getAuthService()
    {
        if ($this->_authService === null) {
            $this->setAuthService(new AuthenticationService());
        }

        return $this->_authService;
    }

}
