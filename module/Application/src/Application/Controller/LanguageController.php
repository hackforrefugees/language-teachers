<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class LanguageController
 * @package Application\Controller
 * @author Dominik Einkemmer
 */
class LanguageController extends AbstractRestfulController
{

    public function indexAction()
    {
        $authService = new AuthenticationService();
        if ($authService->hasIdentity()) {
            $session = $authService->getStorage()->read();
            $userId = $session['userGroup'];
            die(var_dump($userId));
        } else {
            die(var_dump("Not authenticated"));
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