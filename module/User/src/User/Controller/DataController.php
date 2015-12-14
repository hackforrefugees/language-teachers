<?php

namespace User\Controller;


use Application\HelperClasses\AuthenticationHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class DataController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class DataController extends AbstractRestfulController{

    /**
     * Returns all the available security-questions for the application
     * @return JsonModel
     */
    public function getSecurityQuestionsAction(){
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
        $questions = $objectManager->getRepository('Application\Entity\LtSecurityQuestion')->findAll();
        $questionList = array();
        foreach($questions as $question){
            $questionList[$question->getSecurityquestionid()] = $question->getSecurityquestion();
        }
        return new JsonModel($questionList);
    }

}