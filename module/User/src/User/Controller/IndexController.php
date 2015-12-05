<?php

namespace User\Controller;

use Application\Entity\Agent;
use Application\Entity\AgentDescription;
use Application\Entity\Buyer;
use Application\Entity\Image;
use Application\Entity\SecurityQuestion;
use Application\HelperClasses\StandardMailProvider;
use BitDbBcryptAuthAdapter\AuthAdapter;
use User\Form\RegisterFilter;
use User\Form\RegisterForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\InArray;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class IndexController extends AbstractActionController
{

    /**
     * Action that lists all the users that were created by the logged in user
     * Users with the usergroup superadmin see all the users in the system
     * @return ViewModel
     */
    public function registerAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }
        $registerForm = new RegisterForm();
        $registerFilter = new RegisterFilter();

        $userTable = $this->getServiceLocator()->get('UserTable');
        $areaUnitTable = $this->getServiceLocator()->get('AreaUnitTable');
        $currencyTable = $this->getServiceLocator()->get('CurrencyTable');
        $securityQuestionTable = $this->getServiceLocator()->get('SecurityQuestionTable');
        $this->populateSelectFieldsAndAddFilters($userTable, $langCode, $registerForm, $registerFilter, $areaUnitTable, $currencyTable, $securityQuestionTable);

        $registerForm->setInputFilter($registerFilter);

        if ($this->request->isPost()) {
            $post = array_merge_recursive(
                $this->request->getPost()->toArray(), $this->request->getFiles()->toArray()
            );

            $registerForm->setData($post);
            if (!$registerForm->isValid()) {
                return new ViewModel(array(
                    'form' => $registerForm,
                    'error' => true
                ));
            }

            $this->createUser($post, $registerForm, $userTable, $langCode, $securityQuestionTable);

            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(array(
            'form' => $registerForm,
            'error' => false
        ));
    }

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
     * Method for logging in a User
     * @return JsonModel
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\Mvc\Exception\RunTimeException
     * @throws \Zend\Mvc\Exception\DomainException
     * @throws \Zend\Mvc\Exception\InvalidArgumentException
     * @throws \Zend\Authentication\Adapter\DbTable\Exception\RunTimeException
     */
    public function loginAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        if ($this->request->isPost()) {
            $langCode = \Locale::getDefault();

            if (preg_match('/en/', $langCode)) {
                $langCode = 'en_US';
            }
            $authAdapter = new AuthAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
            $authAdapter->setTableName('immo_user');
            $authAdapter->setIdentityColumn('email');
            $authAdapter->setCredentialColumn('password');
            $post = $this->request->getPost()->toArray();
            $authAdapter->setIdentity($post['email']);
            $authAdapter->setCredential($post['password']);

            $result = $authAdapter->authenticate();
            if (!$result->isValid()) {
                return new JsonModel(array('error' => 1, 'message' => $translator->translate('An error occurred while logging in. Please try again.')));
            } else {
                $auth = $this->getServiceLocator()->get('AuthService');
                $userTable = $this->getServiceLocator()->get('UserTable');
                $user = $userTable->getUserByEmail($post['email']);

                if (!$user->getEmailConfirmed()) {
                    return new JsonModel(array('error' => 1, 'message' => $translator->translate('You need to verify your E-Mail-Address before you can login.')));
                }

                $userGroupId = $user->getUserGroupId();
                $userGroup = $userTable->getUserGroupName($userGroupId);
                $storage = $auth->getStorage();
                $rememberMe = $post['rememberMe'];
                if ((int)$rememberMe === 1) {
                    $storage->setRememberMe(1);
                } else {
                    $storage->setRememberMe(0);
                }

                $urlPartToRemove = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
                if ($userGroupId === 3) {
                    $redirectUrl = $this->url()->fromRoute('agentBackend');
                } elseif ($userGroupId === 5) {
                    $redirectUrl = str_replace($urlPartToRemove, '', $this->getRequest()->getHeader('referer')->getUri());
                } else {
                    $redirectUrl = str_replace($urlPartToRemove, '', $this->getRequest()->getHeader('referer')->getUri());
                }

                $storage->write(array('firstName' => $user->getFirstname(), 'lastName' => $user->getLastname(), 'userGroup' => $userGroup, 'userGroupId' => $userGroupId, 'email' => $user->getEmail(), 'userId' => $user->getUserId()));

                return new JsonModel(array('error' => 0, 'message' => $translator->translate('Login Successful'), 'redirectUrl' => $redirectUrl));
            }

        } else {
            $redirectUrl = $this->url()->fromRoute('homeLocale');
            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Wrong request-type'), 'redirectUrl' => $redirectUrl));
        }
    }

    /**
     * @param $userTable
     * @param $langCode
     * @param $registerForm
     * @param $registerFilter
     * @param $areaUnitTable
     * @param $currencyTable
     * @param $securityQuestionTable
     */
    public function populateSelectFieldsAndAddFilters($userTable, $langCode, $registerForm, $registerFilter, $areaUnitTable, $currencyTable, $securityQuestionTable)
    {
        $agentUserGroup = $userTable->getTranslatedUserGroupByName('agent', $langCode);
        $customerUserGroup = $userTable->getTranslatedUserGroupByName('buyer', $langCode);

        $userGroups = array(
            array(
                'value' => $agentUserGroup->getUserGroupId(),
                'label' => $agentUserGroup->getUserGroupDisplayName(),
                'attributes' => array(
                    'data-belongs-to' => 'Agent'
                )
            ),
            array(
                'value' => $customerUserGroup->getUserGroupId(),
                'label' => $customerUserGroup->getUserGroupDisplayName(),
                'attributes' => array(
                    'data-belongs-to' => 'Customer'
                )
            ),
        );

        $registerForm->get('userGroupId')->setValueOptions($userGroups);
        $inArrayValidator = new InArray(array('haystack' => array($agentUserGroup->getUserGroupId(), $customerUserGroup->getUserGroupId())));
        $registerFilter->get('userGroupId')->getValidatorChain()->attach($inArrayValidator);

        $areaUnits = $areaUnitTable->fetchAll($langCode);
        $areaUnitValues = array();
        $areaUnitForSelectField = array();

        foreach ($areaUnits as $areaUnit) {
            $areaUnitValues[] = $areaUnit->getAreaUnitId();
            $optionFields = array(
                'value' => $areaUnit->getAreaUnitId(),
                'label' => $areaUnit->getUnitName() . ' ( ' . $areaUnit->getUnitAbbreviation() . ' )'
            );
            $areaUnitForSelectField[] = $optionFields;
        }

        $registerForm->get('areaUnitId')->setValueOptions($areaUnitForSelectField);
        $inArrayValidator = new InArray(array('haystack' => $areaUnitValues));
        $registerFilter->get('areaUnitId')->getValidatorChain()->attach($inArrayValidator);

        $currencies = $currencyTable->fetchAll($langCode);
        $currencyValues = array();
        $currenciesForSelectField = array();

        foreach ($currencies as $currency) {
            $currencyValues[] = $currency->getCurrencyId();
            $optionFields = array(
                'attributes' => array('data-currency-class' => $currency->getCurrencyFontAwesomeClass()),
                'value' => $currency->getCurrencyId(),
                'label' => $currency->getCurrencyName() . ' ( ' . $currency->getCurrencySign() . ' )'
            );
            $currenciesForSelectField[] = $optionFields;
        }

        $registerForm->get('currencyId')->setValueOptions($currenciesForSelectField);
        $inArrayValidator = new InArray(array('haystack' => $currencyValues));
        $registerFilter->get('currencyId')->getValidatorChain()->attach($inArrayValidator);

        $securityQuestions = $securityQuestionTable->fetchAllForLanguage($langCode);
        $securityQuestionValues = array();
        $securityQuestionsForSelectField = array();

        foreach ($securityQuestions as $securityQuestion) {
            $securityQuestionValues[] = $securityQuestion->getSecurityQuestionId();
            $optionFields = array(
                'value' => $securityQuestion->getSecurityQuestionId(),
                'label' => $securityQuestion->getSecurityQuestion()
            );
            $securityQuestionsForSelectField[] = $optionFields;
        }

        $registerForm->get('securityQuestionId')->setValueOptions($securityQuestionsForSelectField);
        $inArrayValidator = new InArray(array('haystack' => $securityQuestionValues));
        $registerFilter->get('securityQuestionId')->getValidatorChain()->attach($inArrayValidator);
    }

    /**
     * @param $post
     * @param $registerForm
     * @param $userTable
     * @param $langCode
     * @param $securityQuestionTable
     */
    public function createUser($post, $registerForm, $userTable, $langCode, $securityQuestionTable)
    {
        $userGroupId = (int)$post['userGroupId'];

        $userData = array(
            'email' => null,
            'firstName' => null,
            'lastName' => null,
            'userGroupId' => null,
            'gender' => null,
            'registrationToken' => null,
            'emailConfirmed' => 0,
            'birthDate' => null,
            'crypted' => true
        );

        foreach ($userData as $key => $value) {
            if (array_key_exists($key, $post) && $post[$key] !== '' && $post[$key] !== null) {
                $userData[$key] = $post[$key];
            }
        }

        $userData['password'] = $post['passwordRegister'];

        $date = new \DateTime();
        $registrationDate = $date->format('Y-m-d');
        $userData['registrationDate'] = $registrationDate;
        $userData['emailChangedDate'] = $registrationDate;

        $tokenRandomize = uniqid('immo', true);
        $registerToken = md5($post['email'] . $registrationDate . $tokenRandomize);
        $userData['registrationToken'] = $registerToken;

        $formData = $registerForm->getData();
        if($formData['profilePicture']['tmp_name'] !== ''){
            $profilePicture = $formData['profilePicture'];
            $fileName = explode('img/profilePictures/', $profilePicture['tmp_name'])[1];
            $pathToProfilePictures = __DIR__ . '/../../../../../public/img/profilePictures/';
            $fileExtension = pathinfo($profilePicture['tmp_name'], PATHINFO_EXTENSION);
            $fileNameWithoutExtension = str_replace('.' . $fileExtension, '', $fileName);
            $croppedImagePath = $pathToProfilePictures . $fileNameWithoutExtension . '_cropped.' . $fileExtension;
            $croppedImageName = $fileNameWithoutExtension . '_cropped.' . $fileExtension;

            $imagick = new \Imagick($pathToProfilePictures . $fileName);
            $imageData = json_decode($post['profilePictureData']);
            $imagick->cropImage($imageData->width, $imageData->height, $imageData->x, $imageData->y);
            $imagick->writeImage($croppedImagePath);

            $profilePicturePath = 'img/profilePictures';
            $profilePictureName = $croppedImageName;

            $imageTable = $this->getServiceLocator()->get('ImageTable');
            $image = new Image();
            $image->setImagePath($profilePicturePath);
            $image->setImageName($profilePictureName);
            $imageId = $imageTable->saveImage($image);
            $userData['profilePictureId'] = $imageId;
            unlink($profilePicture['tmp_name']);
        }

        if ($userGroupId === 3) {
            $user = new Agent();
            $agentData = array(
                'agencyId' => null,
                'street' => null,
                'streetNumber' => null,
                'zipCode' => null,
                'city' => null,
                'state' => null,
                'country' => null,
                'receivedContractPerMail' => null,
                'contractUploadDate' => null,
                'uploadedContractConfirmed' => null,
                'contractId' => null,
            );

            foreach ($agentData as $key => $value) {
                if (array_key_exists($key, $post) && $post[$key] !== '' && $post[$key] !== null) {
                    $agentData[$key] = $post[$key];
                }
            }
            $user->exchangeArray($agentData, $userData);
            $userId = $userTable->saveUser($user);
            $descriptionData = array(
                'agentId' => $userId,
                'description' => null,
                'shortDescription' => null
            );

            foreach ($descriptionData as $key => $value) {
                if (array_key_exists($key, $post) && $post[$key] !== '' && $post[$key] !== null) {
                    $phoneRegex = '/([0-9]+[\- ]?[0-9]+)/';
                    $emailRegex = '/([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
                    $filteredForPhoneNumber = preg_replace($phoneRegex, '', $post[$key]);
                    $filteredForEmail = preg_replace($emailRegex, '', $filteredForPhoneNumber);
                    $descriptionData[$key] = $filteredForEmail;
                }
            }

            $agentDescription = new AgentDescription();
            $agentDescription->exchangeArray($descriptionData);
            $userTable->saveAgentDescription($agentDescription, $langCode);
        } elseif ($userGroupId === 2) {
            $user = new Buyer();
            $buyerData = array(
                'minBudget' => null,
                'maxBudget' => null,
                'currencyId' => null,
                'areaUnitId' => null,
                'minRent' => null,
                'maxRent' => null
            );
            foreach ($buyerData as $key => $value) {
                if (array_key_exists($key, $post) && $post[$key] !== '' && $post[$key] !== null) {
                    $buyerData[$key] = $post[$key];
                }
            }

            $user->exchangeArray($buyerData, $userData);
            $userId = $userTable->saveUser($user);
        }

        $securityQuestionAnswer = $post['securityQuestionAnswer'];
        $securityQuestionId = (int)$post['securityQuestionId'];

        $securityQuestion = new SecurityQuestion();
        $securityQuestion->setSecurityQuestionId($securityQuestionId);
        $securityQuestionTable->saveSecurityQuestionWithAnswer($securityQuestion, $userId, $securityQuestionAnswer);
        $baseUrl = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
        $homePath = $this->url()->fromRoute('home');
        $verificationLink = $baseUrl . $this->url()->fromRoute('user/verify', array('token' => $registerToken));
        $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
        $standardMailProvider->sendEmailVerificationEmail($user, $registrationDate, $baseUrl, $homePath, $verificationLink);
    }

}

?>