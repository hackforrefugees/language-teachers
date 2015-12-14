<?php
namespace Application;

use Application\Authentication\Storage\RememberMeStorage;
use Application\HelperClasses\AuthenticationNeedHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Method that new defined classes into the service locator
     * @return array
     * @throws \Zend\Db\ResultSet\Exception\InvalidArgumentException
     * @throws \Zend\Db\TableGateway\Exception\InvalidArgumentException
     */
    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(
                //Authentication
                'RememberMeStorage' => function () {
                    return new RememberMeStorage();
                },
                'AuthService' => function ($sm) {
                    $authService = new AuthenticationService();
                    $authService->setStorage($sm->get('RememberMeStorage'));
                    return $authService;
                },
            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }
}
