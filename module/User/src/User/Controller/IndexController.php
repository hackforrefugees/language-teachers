<?php

namespace User\Controller;

use BitDbBcryptAuthAdapter\AuthAdapter;
use User\Form\LoginFilter;
use User\Form\LoginForm;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class IndexController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class IndexController extends AbstractActionController
{

    /**
     * Action that logs a user out in the system
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $authService->clearIdentity();
        $storage = $authService->getStorage();
        $storage->forgetMe();
        return new JsonModel(array('error' => 0));
    }

    /**
     * Action for logging in a user
     * @return JsonModel
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $loginForm = new LoginForm();
            $loginFilter = new LoginFilter();
            $loginForm->setInputFilter($loginFilter);
            $post = $this->request->getPost()->toArray();
            $loginForm->setData($post);
            if (!$loginForm->isValid()) {
                return new JsonModel(array('error' => 1, 'message' => 'You have an error in your form. Please try again'));
            }
            $formData = $loginForm->getData();

            $authAdapter = new AuthAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
            $authAdapter->setTableName('lt_user');
            $authAdapter->setIdentityColumn('email');
            $authAdapter->setCredentialColumn('password');
            $authAdapter->setIdentity($formData['email']);
            $authAdapter->setCredential($formData['password']);

            $result = $authAdapter->authenticate();
            if (!$result->isValid()) {
                return new JsonModel(array('error' => 1, 'message' => 'Error while logging in. Please try again'));
            } else {
                $auth = $this->getServiceLocator()->get('AuthService');
                $objectManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $user = $objectManager->getRepository('Application\Entity\LtUser')
                    ->findOneBy(array('email' => $formData['email']));;

                $storage = $auth->getStorage();
                $rememberMe = $formData['rememberMe'];
                if ((int)$rememberMe === 1) {
                    $storage->setRememberMe(1);
                } else {
                    $storage->setRememberMe(0);
                }

                $storage->write(array('contactName' => $user->getContactname(), 'userGroup' => $user->getUsergroup(), 'email' => $user->getEmail(), 'userId' => $user->getUserId()));
                die(var_dump($auth->getStorage()->read()));
                return new JsonModel(array('error' => 0, 'message' => 'Login successful'));
            }
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array('error' => 1, 'message' => 'Request Method not allowed'));
        }
    }
}

?>