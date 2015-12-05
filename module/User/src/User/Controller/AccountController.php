<?php

namespace User\Controller;

use Application\Entity\Agent;
use Application\Entity\AgentDescription;
use Application\Entity\Buyer;
use Application\Entity\User;
use Application\HelperClasses\StandardMailProvider;
use User\Form\AddDescriptionFilter;
use User\Form\AddDescriptionForm;
use User\Form\RegisterFilter;
use User\Form\RegisterForm;
use User\Form\UpdateProfilePictureFilter;
use User\Form\UpdateProfilePictureForm;
use Zend\Authentication\AuthenticationService;
use Application\Entity\Image;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Exists;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class AccountController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class AccountController extends AbstractActionController
{

    /**
     * Action that shows the profile of a user
     * @return ViewModel
     */
    public function indexAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }

        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($userId);
        $imageTable = $this->getServiceLocator()->get('ImageTable');

        if ($user->getProfilePictureId() === 0) {
            $profilePicture = null;
            $this->layout()->profilePicture = null;
        } else {
            $profilePicture = $imageTable->getImage($user->getProfilePictureId());
            $this->layout()->profilePicture = $profilePicture->getImagePath() . '/' . $profilePicture->getImageName();
        }

        if ($user instanceof Agent) {
            $this->layout('layout/agentBackend');
            $this->layout()->displayName = $user->getLastName() . ' ' . $user->getFirstName();
            $this->layout()->user = $user;
            try {
                $agentDescription = $userTable->getAgentDescription($userId, $langCode);
            } catch (\DomainException $ex) {
                $agentDescription = null;
            }
            $areaUnit = null;
            $currency = null;
        } else {
            $agentDescription = null;
            $areaUnit = null;
            $currency = null;
        }

        if ($user instanceof Buyer) {
            $areaUnitTable = $this->getServiceLocator()->get('AreaUnitTable');
            $areaUnit = $areaUnitTable->getAreaUnitWithLanguageData($user->getAreaUnitId(), $langCode);
            $currencyTable = $this->getServiceLocator()->get('CurrencyTable');
            $currency = $currencyTable->getCurrencyWithLanguageData($user->getCurrencyId(), $langCode);
        }

        $updateProfilePictureForm = new UpdateProfilePictureForm();
        $updateProfilePictureFilter = new UpdateProfilePictureFilter();
        $updateProfilePictureForm->setInputFilter($updateProfilePictureFilter);

        $languageTable = $this->getServiceLocator()->get('LanguageTable');
        $languages = $languageTable->getAllLanguagesNotUsedByAgent($userId);
        $existingLanguages = $languageTable->getAllLanguagesUsedByAgent($userId);

        $languagesNotUsedForAgentDescription = array();
        $languagesUsedForAgentDescription = array();

        $i = 0;
        foreach ($languages as $lngCode => $lang) {
            $languagesNotUsedForAgentDescription[$i]['langCode'] = $lngCode;
            $languagesNotUsedForAgentDescription[$i]['langName'] = $lang['langName'];
            $flag = $imageTable->getImage($lang['flagId']);
            $languagesNotUsedForAgentDescription[$i]['flag'] = $flag->getImagePath() . '/' . $flag->getImageName();
            $i++;
        }

        $i = 0;
        foreach ($existingLanguages as $lang) {
            $languagesUsedForAgentDescription[$i]['langCode'] = $lang->getLangCode();
            $languagesUsedForAgentDescription[$i]['langName'] = $lang->getLanguageName();
            $flag = $imageTable->getImage($lang->getFlagId());
            $languagesUsedForAgentDescription[$i]['flag'] = $flag->getImagePath() . '/' . $flag->getImageName();
            $i++;
        }

        if($this->request->isPost()){
            $post = array_merge_recursive(
                $this->request->getPost()->toArray(), $this->request->getFiles()->toArray()
            );
            $updateProfilePictureForm->setData($post);

            if(!$updateProfilePictureForm->isValid()){
                if ($user->getProfilePictureId() === 0) {
                    $profilePicture = null;
                    $this->layout()->profilePicture = null;
                } else {
                    $profilePicture = $imageTable->getImage($user->getProfilePictureId());
                    $this->layout()->profilePicture = $profilePicture->getImagePath() . '/' . $profilePicture->getImageName();
                }
                return new ViewModel(array(
                    'form' => $updateProfilePictureForm,
                    'user' => $user,
                    'agentDescription' => $agentDescription,
                    'areaUnit' => $areaUnit,
                    'currency' => $currency,
                    'languages' => $languagesNotUsedForAgentDescription,
                    'existingLanguages' => $languagesUsedForAgentDescription,
                ));
            }
            $formData = $updateProfilePictureForm->getData();

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
            $user->setProfilePictureId($imageId);

            $userTable->saveUser($user);
            unlink($profilePicture['tmp_name']);

            if ($user->getProfilePictureId() === 0) {
                $profilePicture = null;
                $this->layout()->profilePicture = null;
            } else {
                $profilePicture = $imageTable->getImage($user->getProfilePictureId());
                $this->layout()->profilePicture = $profilePicture->getImagePath() . '/' . $profilePicture->getImageName();
            }

            return new ViewModel(array(
                'form' => $updateProfilePictureForm,
                'user' => $user,
                'profilePicture' => $profilePicture,
                'agentDescription' => $agentDescription,
                'areaUnit' => $areaUnit,
                'currency' => $currency,
                'languages' => $languagesNotUsedForAgentDescription,
                'existingLanguages' => $languagesUsedForAgentDescription,
            ));
        }

        return new ViewModel(array(
            'form' => $updateProfilePictureForm,
            'user' => $user,
            'profilePicture' => $profilePicture,
            'agentDescription' => $agentDescription,
            'areaUnit' => $areaUnit,
            'currency' => $currency,
            'languages' => $languagesNotUsedForAgentDescription,
            'existingLanguages' => $languagesUsedForAgentDescription,
        ));
    }

    /**
     * Shows and handles the form for adding a new translation for a user-description in the requested language, given that this
     * language is not used yet
     * @return \Zend\Http\Response|ViewModel
     */
    public function addTranslationAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }

        $langCodeFromParams = $this->params()->fromRoute('language', 0);

        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($userId);

        $this->layout('layout/agentBackend');
        $this->layout()->displayName = $user->getLastName() . ' ' . $user->getFirstName();
        $this->layout()->user = $user;

        $imageTable = $this->getServiceLocator()->get('ImageTable');
        $this->layout()->displayName = $user->getLastName() . ' ' . $user->getFirstName();

        if ($user->getProfilePictureId() === 0) {
            $this->layout()->profilePicture = null;
        } else {
            $profilePicture = $imageTable->getImage($user->getProfilePictureId());
            $this->layout()->profilePicture = $profilePicture->getImagePath() . '/' . $profilePicture->getImageName();
        }

        $this->layout()->user = $user;

        $languageTable = $this->getServiceLocator()->get('LanguageTable');
        $userTable = $this->getServiceLocator()->get('UserTable');

        $agentDescriptionForView = $userTable->getAgentDescription($userId, $langCode);

        try {
            $language = $languageTable->getLanguageIfActive($langCodeFromParams);
            $descriptionLanguage = $languageTable->getLanguageIfActive($agentDescriptionForView->getLangCode());
        } catch (\DomainException $ex) {
            return new ViewModel(array(
                'error' => true,
                'languageNotExistentError' => true,
                'language' => $langCodeFromParams,
                'languagesIdenticalError' => false
            ));
        }

        if ($langCodeFromParams === $agentDescriptionForView->getLangCode()) {
            return new ViewModel(array(
                'error' => true,
                'languagesIdenticalError' => true,
                'languageNotExistentError' => false,
                'language' => $langCodeFromParams,
                'agentDescription' => $agentDescriptionForView,
                'descriptionLanguage' => $descriptionLanguage,
            ));
        }

        $addTranslationForm = new AddDescriptionForm();
        $addTranslationFilter = new AddDescriptionFilter();
        $addTranslationForm->setInputFilter($addTranslationFilter);

        if ($this->request->isPost()) {
            $post = $this->request->getPost()->toArray();
            $addTranslationForm->setData($post);
            if (!$addTranslationForm->isValid()) {
                return new ViewModel(array(
                    'form' => $addTranslationForm,
                    'language' => $language,
                    'error' => true,
                    'languageNotExistentError' => false,
                    'languagesIdenticalError' => false,
                    'agentDescription' => $agentDescriptionForView,
                    'descriptionLanguage' => $descriptionLanguage,
                ));
            }
            $formData = $addTranslationForm->getData();
            $agentDescription = new AgentDescription();
            $agentDescription->setAgentId($userId);

            $phoneRegex = '/([0-9]+[\- ]?[0-9]+)/';
            $emailRegex = '/([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
            $filteredForPhoneNumber = preg_replace($phoneRegex, '', $formData['shortDescription']);
            $filteredForEmail = preg_replace($emailRegex, '', $filteredForPhoneNumber);
            $shortDescription = $filteredForEmail;
            $agentDescription->setShortDescription($shortDescription);

            $filteredForPhoneNumber = preg_replace($phoneRegex, '', $formData['description']);
            $filteredForEmail = preg_replace($emailRegex, '', $filteredForPhoneNumber);
            $description = $filteredForEmail;

            $agentDescription->setDescription($description);

            $agentDescription->setDescription($description);
            $userTable->saveAgentDescription($agentDescription, $langCodeFromParams);

            return $this->redirect()->toRoute('user/profile');
        }

        return new ViewModel(array(
            'form' => $addTranslationForm,
            'language' => $language,
            'error' => false,
            'languageNotExistentError' => false,
            'languagesIdenticalError' => false,
            'agentDescription' => $agentDescriptionForView,
            'descriptionLanguage' => $descriptionLanguage
        ));
    }

    /**
     * Shows and handles the form for editing a new translation for a user-description in the requested language
     * @return \Zend\Http\Response|ViewModel
     */
    public function editTranslationAction()
    {
        $langCodeFromParams = $this->params()->fromRoute('language', 0);

        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($userId);
        $imageTable = $this->getServiceLocator()->get('ImageTable');
        $this->layout('layout/agentBackend');
        $this->layout()->displayName = $user->getLastName() . ' ' . $user->getFirstName();
        $this->layout()->user = $user;

        if ($user->getProfilePictureId() === 0) {
            $this->layout()->profilePicture = null;
        } else {
            $profilePicture = $imageTable->getImage($user->getProfilePictureId());
            $this->layout()->profilePicture = $profilePicture->getImagePath() . '/' . $profilePicture->getImageName();
        }
        $this->layout()->user = $user;

        $languageTable = $this->getServiceLocator()->get('LanguageTable');
        $userTable = $this->getServiceLocator()->get('UserTable');

        try {
            $agentDescription = $userTable->getAgentDescription($userId, $langCodeFromParams);
        } catch (\DomainException $ex) {
            return new ViewModel(array(
                'error' => true,
                'languageNotExistentError' => false,
                'descriptionNotExistentError' => true,
                'language' => $langCodeFromParams
            ));
        }

        try {
            $language = $languageTable->getLanguageIfActive($langCodeFromParams);
        } catch (\DomainException $ex) {
            return new ViewModel(array(
                'error' => true,
                'languageNotExistentError' => true,
                'descriptionNotExistentError' => false,
                'language' => $langCodeFromParams,
            ));
        }

        $editTranslationForm = new AddDescriptionForm();
        $editTranslationForm->setData($agentDescription->getArrayCopy());

        $addTranslationFilter = new AddDescriptionFilter();
        $editTranslationForm->setInputFilter($addTranslationFilter);

        if ($this->request->isPost()) {
            $post = $this->request->getPost()->toArray();
            $editTranslationForm->setData($post);
            if (!$editTranslationForm->isValid()) {
                return new ViewModel(array(
                    'form' => $editTranslationForm,
                    'language' => $language,
                    'error' => true,
                    'languageNotExistentError' => false,
                    'descriptionNotExistentError' => false,
                ));
            }
            $formData = $editTranslationForm->getData();


            $phoneRegex = '/([0-9]+[\- ]?[0-9]+)/';
            $emailRegex = '/([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
            $filteredForPhoneNumber = preg_replace($phoneRegex, '', $formData['shortDescription']);
            $filteredForEmail = preg_replace($emailRegex, '', $filteredForPhoneNumber);
            $shortDescription = $filteredForEmail;
            $agentDescription->setShortDescription($shortDescription);

            $filteredForPhoneNumber = preg_replace($phoneRegex, '', $formData['description']);
            $filteredForEmail = preg_replace($emailRegex, '', $filteredForPhoneNumber);
            $description = $filteredForEmail;

            $agentDescription->setDescription($description);
            $userTable->updateAgentDescription($agentDescription, $langCodeFromParams);

            return $this->redirect()->toRoute('user/profile');
        }

        return new ViewModel(array(
            'form' => $editTranslationForm,
            'language' => $language,
            'error' => false,
            'languageNotExistentError' => false,
            'descriptionNotExistentError' => false,
        ));
    }

    /**
     * Deletes an agent-description given that this description exists
     * @return \Zend\Http\Response|ViewModel
     */
    public function deleteTranslationAction()
    {
        $langCode = $this->params()->fromRoute('language', 0);

        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];
        $userTable = $this->getServiceLocator()->get('UserTable');
        try {
            $userTable->deleteAgentDescription($userId, $langCode);
            return $this->redirect()->toRoute('user/profile');
        } catch (\DomainException $ex) {
            return $this->redirect()->toRoute('user/profile');
        }
    }

    /**
     * Action that downloads the contract for a user
     * @return \Zend\Http\Response|\Zend\Stdlib\ResponseInterface
     */
    public function downloadContractAction()
    {
        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($userId);
        if ($user instanceof Agent) {
            $user->setReceivedContractPerMail(1);
            $userTable->saveUser($user);
            $baseDir = __DIR__ . '/../../../../../public/contracts/originals/';
            $fileExistsValidator = new Exists();
            if ($fileExistsValidator->isValid($baseDir . 'example_contract_' . \Locale::getPrimaryLanguage(\Locale::getDefault()) . '.pdf')) {
                $fileContents = file_get_contents($baseDir . 'example_contract_' . \Locale::getPrimaryLanguage(\Locale::getDefault()) . '.pdf');
                $fileName = 'example_contract_' . \Locale::getPrimaryLanguage(\Locale::getDefault()) . '.pdf';
            } else {
                $fileContents = file_get_contents($baseDir . 'example_contract_de.pdf');
                $fileName = 'example_contract_de.pdf';
            }
            $response = $this->getResponse();
            $response->setContent($fileContents);
            $headers = $response->getHeaders();
            $headers->clearHeaders()->addHeaderLine('Content-Type', 'application/pdf')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->addHeaderLine('Content-Length', strlen($fileContents));
            while (ob_get_level()) {
                ob_end_clean();
            }
            return $response;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    /**
     * Deactivates the account of the logged in user
     * @return \Zend\Http\Response|JsonModel
     */
    public function deleteAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        if (!$authService->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $translator = $this->getServiceLocator()->get('translator');

        try {
            $userTable->setInActive($userId);
            $authService->clearIdentity();
            $storage = $authService->getStorage();
            $storage->forgetMe();
            return new JsonModel(array('error' => 0, 'message' => $translator->translate('Your account has been deactivated successfully')));
        } catch (\DomainException $ex) {
            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while deleting Account')));
        }
    }

    /**
     * Shows a message in case a user deleted his account
     * @return ViewModel
     */
    public function successFullyDeletedAction()
    {
        $request = $this->getRequest();
        $referrer = $request->getHeader('referer');
        if (!$referrer) {
            return $this->redirect()->toRoute('home');
        }
        if (!strpos($referrer->getUri(), 'user/profile')) {
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }

    /**
     * Shows and handles the form for editing a users profile
     * @return ViewModel
     */
    public function editAction()
    {
        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = $session['userId'];

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($userId);
        $email = $user->getEmail();

        $editUserForm = new RegisterForm();
        $editUserFilter = new RegisterFilter();

        $editUserForm->get('passwordRegister')->setAttribute('required', false);
        $editUserForm->get('confirmPassword')->setAttribute('required', false);
        $editUserFilter->get('passwordRegister')->setRequired(false);
        $editUserForm->setInputFilter($editUserFilter);

        $this->removeUnNecessaryFields($user, $editUserForm, $editUserFilter);
        $data = $user->getArrayCopy();
        unset($data['password']);
        $editUserForm->setData($data);

        $editUserForm->get('register')->setValue('Edit');

        if($this->request->isPost()){
            $post = $this->request->getPost()->toArray();
            $editUserForm->setData($post);
            if(!array_key_exists('passwordRegister', $post) || trim($post['passwordRegister']) === ''){
                $editUserFilter->get('confirmPassword')->setRequired(false);
                $editUserFilter->get('passwordStrength')->setRequired(false);
                $editUserFilter->get('passwordStrengthScore')->setRequired(false);
                $editUserForm->setInputFilter($editUserFilter);
            }

            if(!$editUserForm->isValid()){
                return new ViewModel(array(
                    'form' => $editUserForm,
                    'user' => $user,
                    'error' => true
                ));
            }

            $formData = $editUserForm->getData();
            if($email !== $formData['email']){
                $user->setEmail($formData['email']);
                $user->setEmailConfirmed(0);
                $date = new \DateTime();
                $emailChangeDate = $date->format('Y-m-d');
                $tokenRandomize = uniqid('immo', true);
                $user->setEmailChangedDate($emailChangeDate);
                $registerToken = md5($formData['email'] . $emailChangeDate . $tokenRandomize);
                $user->setRegistrationToken($registerToken);
                $baseUrl = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
                $homePath = $this->url()->fromRoute('home');
                $verificationLink = $baseUrl . $this->url()->fromRoute('user/verify', array('token' => $registerToken));
                $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
                $standardMailProvider->sendEmailVerificationEmail($user, $emailChangeDate, $baseUrl, $homePath, $verificationLink);
            };

            if(trim($formData['passwordRegister']) !== ''){
                $user->setPassword($formData['passwordRegister']);
            }

            if(trim($formData['firstName']) !== ''){
                $user->setFirstName($formData['firstName']);
            }

            if(trim($formData['lastName']) !== ''){
                $user->setLastName($formData['lastName']);
            }

            if(trim($formData['gender']) !== ''){
                $user->setGender($formData['gender']);
            }

            if(trim($formData['birthDate']) !== ''){
                $user->setBirthDate($formData['birthDate']);
            }

            if($user instanceof Agent){
                if(trim($formData['street']) !== ''){
                    $user->setStreet($formData['street']);
                }

                if(trim($formData['streetNumber']) !== ''){
                    $user->setStreetNumber($formData['streetNumber']);
                }

                if(trim($formData['zipCode']) !== ''){
                    $user->setZipCode($formData['zipCode']);
                }

                if(trim($formData['city']) !== ''){
                    $user->setCity($formData['city']);
                }

                if(trim($formData['state']) !== ''){
                    $user->setState($formData['state']);
                }

                if(trim($formData['country']) !== ''){
                    $user->setCountry($formData['country']);
                }
            } elseif($user instanceof Buyer){
                if(trim($formData['street']) !== ''){
                    $user->setMinBudget($formData['street']);
                } else {
                    $user->setMinBudget(null);
                }

                if(trim($formData['streetNumber']) !== ''){
                    $user->setMaxBudget($formData['streetNumber']);
                } else {
                    $user->setMaxBudget(null);
                }

                if(trim($formData['zipCode']) !== ''){
                    $user->setMinRent($formData['zipCode']);
                } else {
                    $user->setMinRent(null);
                }

                if(trim($formData['city']) !== ''){
                    $user->setMaxRent($formData['city']);
                } else {
                    $user->setMaxRent(null);
                }

                if(trim($formData['state']) !== ''){
                    $user->setCurrencyId($formData['state']);
                } else {
                    $user->setCurrencyId(null);
                }

                if(trim($formData['country']) !== ''){
                    $user->setAreaUnitId($formData['country']);
                } else {
                    $user->setAreaUnitId(null);
                }
            }

            $userTable->saveUser($user);
            return $this->redirect()->toRoute('user/profile');
        }

        return new ViewModel(array(
            'form' => $editUserForm,
            'user' => $user,
            'error' => false
        ));
    }

    /**
     * @param User|Agent|Buyer $user
     * @param RegisterForm $editUserForm
     * @param RegisterFilter $editUserFilter
     */
    public function removeUnNecessaryFields($user, $editUserForm, $editUserFilter)
    {
        $editUserForm->remove('userGroupId');
        $editUserFilter->remove('userGroupId');
        $editUserForm->remove('securityQuestionId');
        $editUserFilter->remove('securityQuestionId');
        $editUserForm->remove('securityQuestionAnswer');
        $editUserFilter->remove('securityQuestionAnswer');
        $editUserForm->remove('profilePictureData');
        $editUserFilter->remove('profilePictureData');
        $editUserForm->remove('profilePictureSrc');
        $editUserFilter->remove('profilePictureSrc');
        $editUserForm->remove('profilePicture');
        $editUserFilter->remove('profilePicture');
        $editUserForm->remove('shortDescription');
        $editUserFilter->remove('shortDescription');
        $editUserForm->remove('description');
        $editUserFilter->remove('description');
        if ($user instanceof Agent) {
            $editUserForm->remove('minBudget');
            $editUserFilter->remove('minBudget');
            $editUserForm->remove('maxBudget');
            $editUserFilter->remove('maxBudget');
            $editUserForm->remove('minRent');
            $editUserFilter->remove('minRent');
            $editUserForm->remove('maxRent');
            $editUserFilter->remove('maxRent');
            $editUserForm->remove('currencyId');
            $editUserFilter->remove('currencyId');
            $editUserForm->remove('areaUnitId');
            $editUserFilter->remove('areaUnitId');
        } elseif ($user instanceof Buyer) {
            $editUserForm->remove('street');
            $editUserFilter->remove('street');
            $editUserForm->remove('streetNumber');
            $editUserFilter->remove('streetNumber');
            $editUserForm->remove('zipCode');
            $editUserFilter->remove('zipCode');
            $editUserForm->remove('city');
            $editUserFilter->remove('city');
            $editUserForm->remove('state');
            $editUserFilter->remove('state');
            $editUserForm->remove('country');
            $editUserFilter->remove('country');
            $editUserForm->remove('country');
            $editUserFilter->remove('country');
        }
    }

}

?>