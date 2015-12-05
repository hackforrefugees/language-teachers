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
     * @ORM\Column(name="userGroup", type="string", length=100, nullable=true)
     */
    private $usergroup;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtSecurityQuestion", inversedBy="userid")
     * @ORM\JoinTable(name="lt_user_security_question",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userId", referencedColumnName="userId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="securityQuestionId", referencedColumnName="securityQuestionId")
     *   }
     * )
     */
    private $securityquestionid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->securityquestionid = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
        $bcrypt = new Bcrypt();
        $this->password = $bcrypt->create($password);
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
     * Add securityquestionid
     *
     * @param \Application\Entity\LtSecurityQuestion $securityquestionid
     *
     * @return LtUser
     */
    public function addSecurityquestionid(\Application\Entity\LtSecurityQuestion $securityquestionid)
    {
        $this->securityquestionid[] = $securityquestionid;

        return $this;
    }

    /**
     * Remove securityquestionid
     *
     * @param \Application\Entity\LtSecurityQuestion $securityquestionid
     */
    public function removeSecurityquestionid(\Application\Entity\LtSecurityQuestion $securityquestionid)
    {
        $this->securityquestionid->removeElement($securityquestionid);
    }

    /**
     * Get securityquestionid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecurityquestionid()
    {
        return $this->securityquestionid;
    }
}
