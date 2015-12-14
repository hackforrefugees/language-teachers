<?php

namespace Application\HelperClasses;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Class AuthenticationNeedHelper
 */
class AuthenticationHelper implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    private function checkAuthorizationNeed($controller, $action)
    {
        $acl = $this->getServiceLocator()->get('Config')['acl'];
        $minUserGroup = $acl['resources']['allow'][$controller][$action];
        if ($minUserGroup === 'anonymous') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks for permissions for a user
     * @param $controller
     * @param $action
     * @param $authTokenObject
     * @return bool
     */
    public function checkPermissions($controller, $action, $authTokenObject)
    {
        $needsAuthorization = $this->checkAuthorizationNeed($controller, $action);
        if ($needsAuthorization) {
            if ($authTokenObject !== false) {
                $authToken = $authTokenObject->getFieldValue();
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $user = $objectManager->getRepository('Application\Entity\LtUser')
                    ->findOneBy(array('authtoken' => $authToken));

                $userGroup = $user->getUsergroup();
                $aclAuth = $this->getServiceLocator()->get('AclAuth');
                $hasPermission = $aclAuth->checkPermission($controller, $action, $userGroup);
                if (!$hasPermission) {
                    return false;
                }
                return true;
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set service locator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}