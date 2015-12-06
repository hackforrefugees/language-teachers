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

    /**
     * Returns all the available languages of the application
     * @return JsonModel
     */
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $languages = $objectManager->getRepository('Application\Entity\LtLanguage')->findAll();
        $languageList = array();
        foreach ($languages as $language) {
            $languageList[$language->getLangcode()] = $language->getLanguagename();
        }
        return new JsonModel($languageList);
    }

}