<?php

/**
 * @namespace
 */
namespace User;

/**
 * @uses User\Event\Authentication
 * @uses User\Form\EditFilter
 * @uses User\Form\EditForm
 * @uses User\Form\RegisterFilter
 * @uses User\Form\RegisterForm
 * @uses User\Form\SetSecurityQuestionFilter
 * @uses User\Form\SetSecurityQuestionForm
 * @uses Zend\ModuleManager\Feature\AutoloaderProviderInterface
 * @uses Zend\Mvc\ModuleRouteListener
 * @uses Zend\Mvc\MvcEvent
 * @uses User\Model\UserTable
 * @uses User\Model\User
 * @uses Zend\Db\ResultSet\ResultSet
 * @uses Zend\Db\TableGateway\TableGateway
 * @uses Zend\ModuleManager\ModuleManager
 * @uses Zend\Mvc\I18n\Translator
 * @uses Zend\Validator\AbstractValidator
 */
use User\Event\Authentication;
use User\Form\EditFilter;
use User\Form\EditForm;
use User\Form\RegisterFilter;
use User\Form\RegisterForm;
use User\Form\SetSecurityQuestionFilter;
use User\Form\SetSecurityQuestionForm;
use User\Model\User;
use User\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

/**
 * Class Module
 * Handles the Bootstrap of the Module
 * It sets the translation as well as telling the module where the config file is and
 * acts with the Service Locator / Autoloader
 * @package User
 * @author Dominik Einkemmer
 */
class Module implements AutoloaderProviderInterface
{

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Handles the Bootstrapping for a Module
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Initialization method for this module
     * called on every page request --> only lightweight operations should be done here
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $sharedManager = $moduleManager->getEventManager()->getSharedManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch', array($this, 'mvcPreDispatch'), 100);
    }

    /**
     * MVC preDispatch Event
     * @param $event
     * @return mixed
     */
    public function mvcPreDispatch($event)
    {
        $di = $event->getTarget()->getServiceManager();
        $auth = $di->get('Authentication');
        return $auth->preDispatch($event);
    }

    /**
     * Method that returns the service config and injects new classes into the service locator
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(
                'Authentication' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Authentication($config);
                }
            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }

}
