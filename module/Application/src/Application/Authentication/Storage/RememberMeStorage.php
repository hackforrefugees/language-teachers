<?php

/**
 * @namespace
 */
namespace Application\Authentication\Storage;

/**
 * @uses Zend\Authentication\Storage
 */
use Zend\Authentication\Storage;

/**
 * Class RememberMeStorage
 * Remember me Storage for the remember me function at the Login
 * @package Application\Authentication\Storage
 * @author Dominik Einkemmer
 */
class RememberMeStorage extends Storage\Session
{

    /**
     * Method that sets the remember Me in the session
     * @param int $rememberMe
     * @param int $time
     */
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    /**
     * Method that forgets a user after logging out
     */
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }

}
