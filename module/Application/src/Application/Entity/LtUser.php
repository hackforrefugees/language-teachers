<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;

/**
 * LtUser
 *
 * @ORM\Table(name="lt_user")
 * @ORM\Entity
 */
class LtUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="contactName", type="string", length=200, nullable=false)
     */
    private $contactname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="profilePicturePath", type="string", length=300, nullable=true)
     */
    private $profilepicturepath;

    /**
     * @var string
     *
     * @ORM\Column(name="userGroup", type="string", length=100, nullable=false)
     */
    private $usergroup;

    /**
     * @var boolean
     *
     * @ORM\Column(name="emailVerfied", type="boolean", nullable=true)
     */
    private $emailverfied;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationDate", type="date", nullable=true)
     */
    private $registrationdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emailChangedDate", type="date", nullable=true)
     */
    private $emailchangeddate;

    /**
     * @var string
     *
     * @ORM\Column(name="registrationToken", type="string", length=300, nullable=true)
     */
    private $registrationtoken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resetExpirationDate", type="date", nullable=true)
     */
    private $resetexpirationdate;

    /**
     * @var string
     *
     * @ORM\Column(name="restRequestHash", type="string", length=300, nullable=true)
     */
    private $restrequesthash;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;



    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return LtUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contactname
     *
     * @param string $contactname
     *
     * @return LtUser
     */
    public function setContactname($contactname)
    {
        $this->contactname = $contactname;

        return $this;
    }

    /**
     * Get contactname
     *
     * @return string
     */
    public function getContactname()
    {
        return $this->contactname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return LtUser
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return LtUser
     */
    public function setPassword($password)
    {
        $bCrypt = new Bcrypt();
        $this->password = $bCrypt->create($password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set profilepicturepath
     *
     * @param string $profilepicturepath
     *
     * @return LtUser
     */
    public function setProfilepicturepath($profilepicturepath)
    {
        $this->profilepicturepath = $profilepicturepath;

        return $this;
    }

    /**
     * Get profilepicturepath
     *
     * @return string
     */
    public function getProfilepicturepath()
    {
        return $this->profilepicturepath;
    }

    /**
     * Set usergroup
     *
     * @param string $usergroup
     *
     * @return LtUser
     */
    public function setUsergroup($usergroup)
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * Get usergroup
     *
     * @return string
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * Set emailverfied
     *
     * @param boolean $emailverfied
     *
     * @return LtUser
     */
    public function setEmailverfied($emailverfied)
    {
        $this->emailverfied = $emailverfied;

        return $this;
    }

    /**
     * Get emailverfied
     *
     * @return boolean
     */
    public function getEmailverfied()
    {
        return $this->emailverfied;
    }

    /**
     * Set registrationdate
     *
     * @param \DateTime $registrationdate
     *
     * @return LtUser
     */
    public function setRegistrationdate($registrationdate)
    {
        $this->registrationdate = $registrationdate;

        return $this;
    }

    /**
     * Get registrationdate
     *
     * @return \DateTime
     */
    public function getRegistrationdate()
    {
        return $this->registrationdate;
    }

    /**
     * Set emailchangeddate
     *
     * @param \DateTime $emailchangeddate
     *
     * @return LtUser
     */
    public function setEmailchangeddate($emailchangeddate)
    {
        $this->emailchangeddate = $emailchangeddate;

        return $this;
    }

    /**
     * Get emailchangeddate
     *
     * @return \DateTime
     */
    public function getEmailchangeddate()
    {
        return $this->emailchangeddate;
    }

    /**
     * Set registrationtoken
     *
     * @param string $registrationtoken
     *
     * @return LtUser
     */
    public function setRegistrationtoken($registrationtoken)
    {
        $this->registrationtoken = $registrationtoken;

        return $this;
    }

    /**
     * Get registrationtoken
     *
     * @return string
     */
    public function getRegistrationtoken()
    {
        return $this->registrationtoken;
    }

    /**
     * Set resetexpirationdate
     *
     * @param \DateTime $resetexpirationdate
     *
     * @return LtUser
     */
    public function setResetexpirationdate($resetexpirationdate)
    {
        $this->resetexpirationdate = $resetexpirationdate;

        return $this;
    }

    /**
     * Get resetexpirationdate
     *
     * @return \DateTime
     */
    public function getResetexpirationdate()
    {
        return $this->resetexpirationdate;
    }

    /**
     * Set restrequesthash
     *
     * @param string $restrequesthash
     *
     * @return LtUser
     */
    public function setRestrequesthash($restrequesthash)
    {
        $this->restrequesthash = $restrequesthash;

        return $this;
    }

    /**
     * Get restrequesthash
     *
     * @return string
     */
    public function getRestrequesthash()
    {
        return $this->restrequesthash;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return LtUser
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return LtUser
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
