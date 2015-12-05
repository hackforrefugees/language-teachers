<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class DataController
 * @package Application\Controller
 * @author Dominik Einkemmer
 */
class DataController extends AbstractRestfulController{

    public function getLanguagesAction(){
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $languages = $objectManager->getRepository('Application\Entity\LtLanguage')->findAll();
        $languageList = array();
        foreach($languages as $language){
            $languageList[$language->getLangcode()] = $language->getLanguagename();
        }
        return new JsonModel($languageList);
    }

}