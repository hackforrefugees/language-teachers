<?php

namespace User\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class DataController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class DataController extends AbstractRestfulController{

    public function getSecurityQuestionsAction(){
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $questions = $objectManager->getRepository('Application\Entity\LtSecurityQuestion')->findAll();
        $questionList = array();
        foreach($questions as $question){
            $questionList[$question->getSecurityquestionid()] = $question->getSecurityquestion();
        }
        return new JsonModel($questionList);
    }

}