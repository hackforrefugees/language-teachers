<?php

namespace User\Controller;

use Application\HelperClasses\StandardMailProvider;
use User\Form\ForgotPasswordFilter;
use User\Form\ForgotPasswordForm;
use User\Form\RequestVerificationLinkFilter;
use User\Form\RequestVerificationLinkForm;
use User\Form\ResetPasswordFilter;
use User\Form\ResetPasswordForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\InArray;
use Zend\View\Model\ViewModel;

/**
 * Class VerificationController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class VerificationController extends AbstractActionController
{

    /**
     * Shows the right message to the user who clicked on his verification link
     * In case the link is outdated a new email will be send to the user
     * In case the link was invalid, the user gets the chance to request a new verification link
     * through a form
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $requestLinkForm = new RequestVerificationLinkForm();
        $requestLinkForm->setInputFilter(new RequestVerificationLinkFilter());
        $userTable = $this->getServiceLocator()->get('UserTable');
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $requestLinkForm->setData($post);
            if (!$requestLinkForm->isValid()) {
                return new ViewModel(array(
                    'error' => true,
                    'outDatedError' => false,
                    'form' => $requestLinkForm
                ));
            }

            try {
                $user = $userTable->getUserByEmail($post['email']);
                if ($user->getEmailConfirmed() !== 1) {
                    $date = new \DateTime();
                    $emailChangedDate = $date->format('Y-m-d');
                    $user->setRegistrationDate($emailChangedDate);

                    $tokenRandomize = uniqid('immo', true);
                    $registerToken = md5($post['email'] . $emailChangedDate . $tokenRandomize);
                    $user->setRegistrationToken($registerToken);

                    $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
                    $standardMailProvider->sendEmailVerificationEmail($registerToken, $user);
                }
            } catch (\DomainException $ex) {
                return $this->redirect()->toRoute('user/requestLink');
            }

            return $this->redirect()->toRoute('user/requestLink');
        }

        $token = $this->params()->fromRoute('token', 0);

        if (!$token) {
            return new ViewModel(array(
                'error' => true,
                'outDatedError' => false,
                'form' => $requestLinkForm
            ));
        }


        try {
            $user = $userTable->getUserByRegistrationToken($token);

            $emailChangedDate = $user->getEmailChangedDate();

            $today = new \DateTime();

            $latestVerificationDate = new \DateTime($emailChangedDate);
            $latestVerificationDate->modify('+ 2 days');

            if ($today > $latestVerificationDate) {
                $date = new \DateTime();
                $emailChangedDate = $date->format('Y-m-d');
                $user->setEmailChangedDate($emailChangedDate);

                $tokenRandomize = uniqid('immo', true);
                $registerToken = md5($user->getEmail() . $emailChangedDate . $tokenRandomize);
                $user->setRegistrationToken($registerToken);
                $userTable->saveUser($user);

                $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
                $baseUrl = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
                $homePath = $this->url()->fromRoute('home');
                $verificationLink = $baseUrl . $this->url()->fromRoute('user/verify', array('token' => $registerToken));
                $standardMailProvider->sendEmailVerificationEmail($user, $emailChangedDate, $baseUrl, $homePath, $verificationLink);
                return new ViewModel(array(
                    'error' => true,
                    'outDatedError' => true
                ));
            }

            $user->setEmailConfirmed(1);
            $user->setRegistrationToken(null);
            $userTable->saveUser($user);
        } catch (\DomainException $ex) {
            return new ViewModel(array(
                'error' => true,
                'outDatedError' => false,
                'form' => $requestLinkForm
            ));
        }

        return new ViewModel(array(
            'error' => false
        ));
    }

    /**
     * Shows a message to the user if he requested a new verification link
     * @return ViewModel
     */
    public function requestLinkAction()
    {
        return new ViewModel();
    }

    /**
     * Shows and handles the form for requesting a password reset
     * @return ViewModel
     */
    public function forgotPasswordAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }

        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordFilter = new ForgotPasswordFilter();
        $forgotPasswordForm->setInputFilter($forgotPasswordFilter);

        $securityQuestionTable = $this->getServiceLocator()->get('SecurityQuestionTable');

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

        $forgotPasswordForm->get('securityQuestionId')->setValueOptions($securityQuestionsForSelectField);
        $inArrayValidator = new InArray(array('haystack' => $securityQuestionValues));
        $forgotPasswordFilter->get('securityQuestionId')->getValidatorChain()->attach($inArrayValidator);

        if($this->request->isPost()){
            $post = $this->request->getPost()->toArray();
            $forgotPasswordForm->setData($post);
            if(!$forgotPasswordForm->isValid()){
                return new ViewModel(array(
                    'form' => $forgotPasswordForm,
                    'error' => true,
                    'submitted' => true
                ));
            }

            $formData = $forgotPasswordForm->getData();
            $userTable = $this->getServiceLocator()->get('UserTable');
            $email = $formData['email'];
            $answer = $formData['securityQuestionAnswer'];
            $questionId = $formData['securityQuestionId'];
            $hash = sha1($email . $answer . $questionId);

            try {
                $user = $userTable->getUserByEmail($email);
                $resetExpirationDate = new \DateTime();
                $resetExpirationDate->modify('+24 hours');
                $expirationDate = $resetExpirationDate->format('Y-m-d H:i:s');
                $userTable->setPasswordRequest($user->getUserId(), $expirationDate, $hash);

                $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
                $baseUrl = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
                $homePath = $this->url()->fromRoute('home');
                $verificationLink = $baseUrl . $this->url()->fromRoute('user/resetPassword', array('hash' => $hash));
                $standardMailProvider->sendForgotPasswordEmail($user, $expirationDate, $baseUrl, $homePath, $verificationLink);
                return new ViewModel(array(
                    'form' => $forgotPasswordForm,
                    'submitted' => true
                ));
            } catch (\Exception $ex) {
                return new ViewModel(array(
                    'form' => $forgotPasswordForm,
                    'submitted' => true
                ));
            }
        }

        return new ViewModel(array(
            'form' => $forgotPasswordForm,
            'error' => false,
            'submitted' => false
        ));
    }

    /**
     * Shows and handles the form for entering a new password if a password reset was requested and valid
     * @return ViewModel
     */
    public function resetPasswordAction()
    {
        $hash = $this->params()->fromRoute('hash', 0);

        if (!$hash) {
            return $this->redirect()->toRoute('home');
        }

        $userTable = $this->getServiceLocator()->get('UserTable');
        try {
            $user = $userTable->getUserByPasswordResetHash($hash);
        } catch (\Exception $ex) {
            return new ViewModel(array(
                'valid' => false,
                'hash' => $hash
            ));
        }
        $resetPasswordForm = new ResetPasswordForm();
        $resetPasswordFilter = new ResetPasswordFilter();
        $resetPasswordForm->setInputFilter($resetPasswordFilter);


        if($this->request->isPost()){
            $post = $this->request->getPost()->toArray();
            $resetPasswordForm->setData($post);
            if(!$resetPasswordForm->isValid()){
                return new ViewModel(array(
                    'form' => $resetPasswordForm,
                    'error' => true,
                    'valid' => true,
                    'hash' => $hash
                ));
            }
            $formData = $resetPasswordForm->getData();
            $newPassword = $formData['password'];
            $user->setPassword($newPassword);
            $userTable->updateUserData($user);
            $userTable->unsetPasswordRequest($user->getUserId());
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(array(
            'form' => $resetPasswordForm,
            'error' => false,
            'valid' => true,
            'hash' => $hash
        ));
    }

}

?>