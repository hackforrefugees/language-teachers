<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class LtUser extends \Application\Entity\LtUser implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'userid', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'email', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'contactname', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'phone', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'password', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'profilepicturepath', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'usergroup', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'emailverfied', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'registrationdate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'emailchangeddate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'registrationtoken', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'resetexpirationdate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'restrequesthash', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'latitude', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'longitude', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'securityquestionid');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'userid', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'email', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'contactname', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'phone', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'password', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'profilepicturepath', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'usergroup', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'emailverfied', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'registrationdate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'emailchangeddate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'registrationtoken', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'resetexpirationdate', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'restrequesthash', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'latitude', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'longitude', '' . "\0" . 'Application\\Entity\\LtUser' . "\0" . 'securityquestionid');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (LtUser $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getUserid()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getUserid();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserid', array());

        return parent::getUserid();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setContactname($contactname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContactname', array($contactname));

        return parent::setContactname($contactname);
    }

    /**
     * {@inheritDoc}
     */
    public function getContactname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactname', array());

        return parent::getContactname();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone($phone)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', array($phone));

        return parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', array());

        return parent::getPhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', array($password));

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', array());

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setProfilepicturepath($profilepicturepath)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setProfilepicturepath', array($profilepicturepath));

        return parent::setProfilepicturepath($profilepicturepath);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfilepicturepath()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProfilepicturepath', array());

        return parent::getProfilepicturepath();
    }

    /**
     * {@inheritDoc}
     */
    public function setUsergroup($usergroup)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsergroup', array($usergroup));

        return parent::setUsergroup($usergroup);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsergroup()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsergroup', array());

        return parent::getUsergroup();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmailverfied($emailverfied)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmailverfied', array($emailverfied));

        return parent::setEmailverfied($emailverfied);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmailverfied()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmailverfied', array());

        return parent::getEmailverfied();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationdate($registrationdate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationdate', array($registrationdate));

        return parent::setRegistrationdate($registrationdate);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationdate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationdate', array());

        return parent::getRegistrationdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmailchangeddate($emailchangeddate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmailchangeddate', array($emailchangeddate));

        return parent::setEmailchangeddate($emailchangeddate);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmailchangeddate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmailchangeddate', array());

        return parent::getEmailchangeddate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationtoken($registrationtoken)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationtoken', array($registrationtoken));

        return parent::setRegistrationtoken($registrationtoken);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationtoken()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationtoken', array());

        return parent::getRegistrationtoken();
    }

    /**
     * {@inheritDoc}
     */
    public function setResetexpirationdate($resetexpirationdate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setResetexpirationdate', array($resetexpirationdate));

        return parent::setResetexpirationdate($resetexpirationdate);
    }

    /**
     * {@inheritDoc}
     */
    public function getResetexpirationdate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResetexpirationdate', array());

        return parent::getResetexpirationdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRestrequesthash($restrequesthash)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRestrequesthash', array($restrequesthash));

        return parent::setRestrequesthash($restrequesthash);
    }

    /**
     * {@inheritDoc}
     */
    public function getRestrequesthash()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRestrequesthash', array());

        return parent::getRestrequesthash();
    }

    /**
     * {@inheritDoc}
     */
    public function setLatitude($latitude)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLatitude', array($latitude));

        return parent::setLatitude($latitude);
    }

    /**
     * {@inheritDoc}
     */
    public function getLatitude()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLatitude', array());

        return parent::getLatitude();
    }

    /**
     * {@inheritDoc}
     */
    public function setLongitude($longitude)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLongitude', array($longitude));

        return parent::setLongitude($longitude);
    }

    /**
     * {@inheritDoc}
     */
    public function getLongitude()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLongitude', array());

        return parent::getLongitude();
    }

    /**
     * {@inheritDoc}
     */
    public function addSecurityquestionid(\Application\Entity\LtSecurityQuestion $securityquestionid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addSecurityquestionid', array($securityquestionid));

        return parent::addSecurityquestionid($securityquestionid);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSecurityquestionid(\Application\Entity\LtSecurityQuestion $securityquestionid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeSecurityquestionid', array($securityquestionid));

        return parent::removeSecurityquestionid($securityquestionid);
    }

    /**
     * {@inheritDoc}
     */
    public function getSecurityquestionid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSecurityquestionid', array());

        return parent::getSecurityquestionid();
    }

}
