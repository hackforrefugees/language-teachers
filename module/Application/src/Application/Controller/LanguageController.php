<?php

namespace Application\Controller;

use Application\HelperClasses\AuthenticationHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class LanguageController
 * @package Application\Controller
 * @author Dominik Einkemmer
 */
class LanguageController extends AbstractRestfulController
{

    /**
     * Returns all the available languages of the application
     * @return JsonModel
     */
    public function indexAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource .'));
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $languages = $objectManager->getRepository('Application\Entity\LtLanguage')->findAll();
        $languageList = array();
        foreach ($languages as $language) {
            $languageList[$language->getLangcode()] = $language->getLanguagename();
        }
        return new JsonModel($languageList);
    }

}