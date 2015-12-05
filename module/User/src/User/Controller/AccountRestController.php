<?php

namespace User\Controller;

use Application\Entity\Buyer;
use Application\HelperClasses\StandardMailProvider;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Validator\EmailAddress;
use Zend\View\Model\JsonModel;

/**
 * Class AccountRestController
 * @package User\Controller
 * @author Dominik Einkemmer
 */
class AccountRestController extends AbstractRestfulController
{

    /**
     * Action that allows inline editing of selected properties of a user through the profile
     * @return JsonModel
     */
    public function inlineEditAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        if ($this->request->isPost()) {
            $post = $this->request->getPost()->toArray();

            if (!array_key_exists('pk', $post)) {
                return new JsonModel(array('error' => 1, 'message' => $translator->translate('Action not allowed')));
            }
            $formPk = (int)$post['pk'];

            $authService = new AuthenticationService();
            $session = $authService->getStorage()->read();
            $userId = $session['userId'];

            if ($formPk !== $userId) {
                return new JsonModel(array('error' => 1, 'message' => $translator->translate('Action not allowed')));
            }

            $userTable = $this->getServiceLocator()->get('UserTable');
            $user = $userTable->getUser($userId);

            if (array_key_exists('name', $post) && array_key_exists('value', $post)) {
                if ($user instanceof Buyer) {
                    $currencyTable = $this->getServiceLocator()->get('CurrencyTable');
                    if ($user->getCurrencyId() !== null) {
                        $currentCurrency = $currencyTable->getCurrency($user->getCurrencyId());
                    } else {
                        $currentCurrency = $currencyTable->getCurrency(1);
                    }
                    if ($post['name'] === 'minRent') {
                        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
                        $currencyFormat = $viewHelperManager->get('currencyFormat');
                        $minRent = (double)$post['value'];
                        $user->setMinRent($minRent);
                        $formattedNewValue = $currencyFormat($minRent, $currentCurrency->getCurrencyCode(), null, \Locale::getDefault());
                        return $this->updateUser($translator, $user, $userTable, $formattedNewValue);
                    } elseif ($post['name'] === 'maxRent') {
                        $maxRent = (double)$post['value'];
                        $user->setMaxRent($maxRent);
                        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
                        $currencyFormat = $viewHelperManager->get('currencyFormat');
                        $formattedNewValue = $currencyFormat($maxRent, $currentCurrency->getCurrencyCode(), null, \Locale::getDefault());
                        return $this->updateUser($translator, $user, $userTable, $formattedNewValue);
                    } elseif ($post['name'] === 'minBudget') {
                        $minBudget = (double)$post['value'];
                        $user->setMinBudget($minBudget);
                        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
                        $currencyFormat = $viewHelperManager->get('currencyFormat');
                        $formattedNewValue = $currencyFormat($minBudget, $currentCurrency->getCurrencyCode(), null, \Locale::getDefault());
                        return $this->updateUser($translator, $user, $userTable, $formattedNewValue);
                    } elseif ($post['name'] === 'maxBudget') {
                        $maxBudget = (double)$post['value'];
                        $user->setMaxBudget($maxBudget);
                        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
                        $currencyFormat = $viewHelperManager->get('currencyFormat');
                        $formattedNewValue = $currencyFormat($maxBudget, $currentCurrency->getCurrencyCode(), null, \Locale::getDefault());
                        return $this->updateUser($translator, $user, $userTable, $formattedNewValue);
                    } elseif ($post['name'] === 'areaUnitId') {
                        $areaUnitId = $post['value'];
                        $areaUnitTable = $this->getServiceLocator()->get('AreaUnitTable');
                        try {
                            $areaUnitTable->getAreaUnit($areaUnitId);
                            $user->setAreaUnitId($areaUnitId);
                            return $this->updateUser($translator, $user, $userTable);
                        } catch (\DomainException $ex) {
                            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while updating')));
                        }
                    } elseif ($post['name'] === 'currencyId') {
                        $currencyId = $post['value'];
                        try {
                            $currencyTable->getCurrency($currencyId);
                            $user->setCurrencyId($currencyId);
                            return $this->updateUser($translator, $user, $userTable);
                        } catch (\DomainException $ex) {
                            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while updating')));
                        }
                    }
                }

                if ($post['name'] === 'email') {
                    $newEmail = $post['value'];
                    $mailValidator = new EmailAddress(array('domain' => true));
                    if ($mailValidator->isValid($newEmail)) {
                        try {
                            $userTable->getUserByEmail($newEmail);
                            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Invalid')));
                        } catch (\DomainException $ex) {
                            $user->setEmail($newEmail);
                            $user->setEmailConfirmed(0);
                            $date = new \DateTime();
                            $emailChangeDate = $date->format('Y-m-d');
                            $tokenRandomize = uniqid('immo', true);
                            $user->setEmailChangedDate($emailChangeDate);
                            $registerToken = md5($newEmail . $emailChangeDate . $tokenRandomize);
                            $user->setRegistrationToken($registerToken);
                            $baseUrl = 'http://' . str_replace('Host: ', '', $this->getRequest()->getHeader('host')->toString());
                            $homePath = $this->url()->fromRoute('home');
                            $verificationLink = $baseUrl . $this->url()->fromRoute('user/verify', array('token' => $registerToken));
                            $standardMailProvider = new StandardMailProvider($this->getServiceLocator());
                            $standardMailProvider->sendEmailVerificationEmail($user, $emailChangeDate, $baseUrl, $homePath, $verificationLink);
                            return $this->updateUser($translator, $user, $userTable);
                        }
                    }
                    return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while updating')));
                }

                if ($post['name'] === 'gender') {
                    $gender = $post['value'];
                    $user->setGender($gender);
                    return $this->updateUser($translator, $user, $userTable);
                }

                if ($post['name'] === 'birthday') {
                    $newBirthday = $post['value'];
                    $user->setBirthDate($newBirthday);
                    return $this->updateUser($translator, $user, $userTable);
                }

                return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while updating')));
            }
            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Invalid')));
        }
        return new JsonModel(array('error' => 1, 'message' => $translator->translate('Wrong request type.')));
    }

    private function updateUser($translator, $user, $userTable, $formattedNewValue = null)
    {
        try {
            $userTable->saveUser($user);
            return new JsonModel(array('error' => 0, 'message' => $translator->translate('Successfully updated.'), 'formattedNewValue' => $formattedNewValue));
        } catch (\Exception $ex) {
            return new JsonModel(array('error' => 1, 'message' => $translator->translate('Error while updating'), 'formattedNewValue' => $formattedNewValue));
        }
    }

    public function currenciesAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }

        $currencyTable = $this->getServiceLocator()->get('CurrencyTable');
        $currencies = $currencyTable->fetchAll($langCode);
        $currencyList = array();
        foreach ($currencies as $currency) {
            $currencyList[] = array('value' => $currency->getCurrencyId(), 'text' => $currency->getCurrencyName() . ' (' . $currency->getCurrencySign() . ')');
        }
        $content = json_encode($currencyList);
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        $response->setContent($content);
        while (ob_get_level()) {
            ob_end_clean();
        }
        return $response;
    }

    public function areaUnitsAction()
    {
        $langCode = \Locale::getDefault();

        if (preg_match('/en/', $langCode)) {
            $langCode = 'en_US';
        }

        $areaUnitTable = $this->getServiceLocator()->get('AreaUnitTable');
        $areaUnits = $areaUnitTable->fetchAll($langCode);
        $areaUnitList = array();
        foreach ($areaUnits as $areaUnit) {
            $areaUnitList[] = array('value' => $areaUnit->getAreaUnitId(), 'text' => $areaUnit->getUnitName() . ' (' . $areaUnit->getUnitAbbreviation() . ')');
        }
        $content = json_encode($areaUnitList);
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        $response->setContent($content);
        while (ob_get_level()) {
            ob_end_clean();
        }
        return $response;
    }
}

?>