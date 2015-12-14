<?php

namespace User\Controller;

use Application\Entity\LtOrganisation;
use Application\Entity\LtStudent;
use Application\Entity\LtUser;
use Application\Entity\LtUserSecurityQuestion;
use Application\Entity\LtVolunteer;
use Application\HelperClasses\AuthenticationHelper;
use BitDbBcryptAuthAdapter\AuthAdapter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use User\Form\LoginFilter;
use User\Form\LoginForm;
use User\Form\RegisterFilter;
use User\Form\RegisterForm;
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
     * Action for registering a user
     * @return JsonModel
     */
    public function registerAction()
    {
        if ($this->request->isOptions()) {
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
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        if ($this->request->isPost()) {
            $registerForm = new RegisterForm();
            $registerFilter = new RegisterFilter();
            $registerForm->setInputFilter($registerFilter);
            $post = get_object_vars(json_decode($this->request->getContent()));
            $registerForm->setData($post);
            if (!$registerForm->isValid()) {
                $errorMessages = array();
                foreach ($registerForm->getMessages() as $elementName => $messages) {
                    foreach ($messages as $message) {
                        $errorMessages[$elementName] = $message;
                    }
                }
                return new JsonModel(array('error' => 1, 'message' => 'You have an error in your form. Please try again.', 'formErrors' => $errorMessages));
            }
            $formData = $registerForm->getData();
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $user = $objectManager->getRepository('Application\Entity\LtUser')
                ->findOneBy(array('email' => $formData['email']));;

            if ($user !== null) {
                return new JsonModel(array('error' => 1, 'message' => 'E-Mail already in use'));
            }

            $hydrator = new DoctrineObject($objectManager);
            $user = new LtUser();
            $user = $hydrator->hydrate($formData, $user);

            $date = new \DateTime();
            $user->setRegistrationdate($date);
            $user->setEmailchangeddate($date);

            $tokenRandomize = uniqid(mt_rand(1, 100), true);
            $registerToken = md5($formData['email'] . $date->format('Y-m-d') . $tokenRandomize);
            $user->setRegistrationtoken($registerToken);

            $userType = $formData['userType'];

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . str_replace(' ', '+', $formData['region']) . '&sensor=true';
            $googleData = file_get_contents($url);
            $googleDataArray = json_decode($googleData, true);
            $latitude = $googleDataArray['results'][0]['geometry']['location']['lat'];
            $longitude = $googleDataArray['results'][0]['geometry']['location']['lng'];

            $user->setLatitude($latitude);
            $user->setLongitude($longitude);

            list($type, $data) = explode(';', $post['profilePicturePath']);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $randomImageName = md5($formData['email'] . $tokenRandomize);
            if (strpos($type, 'png')) {
                $imageName = $randomImageName . '.png';
            } else {
                $imageName = $randomImageName . '.jpg';
            }

            file_put_contents(__DIR__ . '/../../../../../public/app/img/profilePictures/' . $imageName, $data);

            $user->setProfilepicturepath('img/profilePictures/' . $imageName);

            if ($userType === 'student') {
                $student = new LtStudent();
                $language = $objectManager->find('Application\Entity\LtLanguage', $formData['nativeLanguage']);
                $student->setStudentid($user);
                $student->setNativelanguage($language);
                $user->setUsergroup('student');
                $objectManager->persist($user);
                $objectManager->flush();
                $objectManager->persist($student);
            } elseif ($userType === 'volunteer') {
                $volunteer = new LtVolunteer();
                $language = $objectManager->find('Application\Entity\LtLanguage', $formData['nativeLanguage']);
                $volunteer->setVolunteerid($user);
                $volunteer->setNativelanguage($language);

                if (array_key_exists('languages', $formData)) {
                    $languageSkills = $formData['languages'];
                    foreach ($languageSkills as $languageSkill) {
                        $tempLanguage = $objectManager->find('Application\Entity\LtLanguage', $languageSkill);
                        $volunteer->addLangcode($tempLanguage);
                    }
                }
                $user->setUsergroup('volunteer');
                $objectManager->persist($user);
                $objectManager->flush();
                $objectManager->persist($volunteer);
            } elseif ($userType === 'organisation') {
                $organisation = new LtOrganisation();
                $organisation->setOrganisationid($user);
                $organisation->setContactpersonname($formData['contactPersonName']);
                $organisation->setContactpersonemail($formData['contactPersonEmail']);
                if (array_key_exists('contactPersonPhone', $formData) && trim($formData['contactPersonPhone']) !== '') {
                    $organisation->setContactpersonemail($formData['contactPersonPhone']);
                }
                if (array_key_exists('organisationDescription', $formData) && trim($formData['organisationDescription']) !== '') {
                    $organisation->setOrganisationdescription($formData['organisationDescription']);
                }
                if (array_key_exists('organisationWebsite', $formData) && trim($formData['organisationWebsite']) !== '') {
                    $organisation->setOrganisationwebsite($formData['organisationWebsite']);
                }
                $user->setUsergroup('organisation');
                $objectManager->persist($user);
                $objectManager->flush();
                $objectManager->persist($organisation);
            }

            $userSecurityQuestion = new LtUserSecurityQuestion();
            $userSecurityQuestion->setSecurityquestionid($formData['securityQuestionId']);
            $userSecurityQuestion->setLangcode('en');
            $userSecurityQuestion->setUserid($user);
            $userSecurityQuestion->setSecurityquestionanswer($formData['securityQuestionAnswer']);

            $objectManager->persist($userSecurityQuestion);
            $objectManager->flush();
            $this->response->setStatusCode(201);
            return new JsonModel(array('error' => 0, 'message' => 'Account created successfully.'));
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array('error' => 1, 'message' => 'Request-Method not allowed'));
        }
    }

    /**
     * Action that logs a user out in the system
     * @return \Zend\Http\Response
     */
    public function logoutAction()
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
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = $objectManager->getRepository('Application\Entity\LtUser')
            ->findOneBy(array('authtoken' => $authTokenObject->getFieldValue()));

        $user->setAuthToken(null);
        $objectManager->persist($user);
        $objectManager->flush();

        return new JsonModel(array('error' => 0, 'message' => 'Logout successful'));
    }

    /**
     * Returns an error message in case a user is not authorized
     * @return \Zend\Http\Response
     */
    public function notAllowedAction()
    {
        $this->response->setStatusCode(401);
        return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
    }

    /**
     * Action for logging in a user
     * @return JsonModel
     */
    public function loginAction()
    {
        if ($this->request->isOptions()) {
            return new JsonModel();
        }

        if ($this->request->isPost()) {
            $loginForm = new LoginForm();
            $loginFilter = new LoginFilter();
            $loginForm->setInputFilter($loginFilter);
            $post = get_object_vars(json_decode($this->request->getContent()));

            $loginForm->setData($post);
            if (!$loginForm->isValid()) {
                $errorMessages = array();
                foreach ($loginForm->getMessages() as $elementName => $messages) {
                    foreach ($messages as $message) {
                        $errorMessages[$elementName] = $message;
                    }
                }

                return new JsonModel(array('error' => 1, 'message' => 'You have an error in your form. Please try again.', 'formErrors' => $errorMessages));
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
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $user = $objectManager->getRepository('Application\Entity\LtUser')
                    ->findOneBy(array('email' => $formData['email']));

                $date = new \DateTime();
                $hashRandomize = uniqid('swetea', true);
                $authToken = md5($formData['email'] . $date->format('Y-m-d') . $hashRandomize);
                $user->setAuthtoken($authToken);

                $storage = $auth->getStorage();
                $storage->write(array('contactName' => $user->getContactname(), 'userGroup' => $user->getUsergroup(), 'email' => $user->getEmail(), 'userId' => $user->getUserId()));
                $objectManager->persist($user);
                $objectManager->flush();
                return new JsonModel(array('error' => 0, 'message' => 'Login successful', 'authToken' => $authToken, 'userGroup' => $user->getUsergroup()));
            }
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array('error' => 1, 'message' => 'Request Method not allowed'));
        }
    }

    public function indexAction()
    {
        if ($this->request->isOptions()) {
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
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = $objectManager->getRepository('Application\Entity\LtUser')
            ->findOneBy(array('authtoken' => $authTokenObject->getFieldValue()));

        $userArray = array(
            'email' => $user->getEmail(),
            'contactName' => $user->getContactname(),
            'profilePicturePath' => $user->getProfilepicturepath(),
            'registrationDate' => $user->getRegistrationdate()->format('Y-m-d'),
        );

        if ($user->getPhone() !== '') {
            $userArray['phone'] = $user->getPhone();
        } else {
            $userArray['phone'] = null;
        }

        return new JsonModel($userArray);
    }
}

?>