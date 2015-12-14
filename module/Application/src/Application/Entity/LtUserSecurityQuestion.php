<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtUserSecurityQuestion
 *
 * @ORM\Table(name="lt_user_security_question", indexes={@ORM\Index(name="IDX_C0F67E864B64DCC", columns={"userId"})})
 * @ORM\Entity
 */
class LtUserSecurityQuestion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="securityQuestionId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $securityquestionid;

    /**
     * @var string
     *
     * @ORM\Column(name="securityQuestionAnswer", type="string", length=200, nullable=true)
     */
    private $securityquestionanswer;

    /**
     * @var string
     *
     * @ORM\Column(name="langCode", type="string", length=5, nullable=true)
     */
    private $langcode;

    /**
     * @var \Application\Entity\LtUser
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\LtUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId", referencedColumnName="userId")
     * })
     */
    private $userid;



    /**
     * Set securityquestionid
     *
     * @param integer $securityquestionid
     *
     * @return LtUserSecurityQuestion
     */
    public function setSecurityquestionid($securityquestionid)
    {
        $this->securityquestionid = $securityquestionid;
    
        return $this;
    }

    /**
     * Get securityquestionid
     *
     * @return integer
     */
    public function getSecurityquestionid()
    {
        return $this->securityquestionid;
    }

    /**
     * Set securityquestionanswer
     *
     * @param string $securityquestionanswer
     *
     * @return LtUserSecurityQuestion
     */
    public function setSecurityquestionanswer($securityquestionanswer)
    {
        $this->securityquestionanswer = $securityquestionanswer;
    
        return $this;
    }

    /**
     * Get securityquestionanswer
     *
     * @return string
     */
    public function getSecurityquestionanswer()
    {
        return $this->securityquestionanswer;
    }

    /**
     * Set langcode
     *
     * @param string $langcode
     *
     * @return LtUserSecurityQuestion
     */
    public function setLangcode($langcode)
    {
        $this->langcode = $langcode;
    
        return $this;
    }

    /**
     * Get langcode
     *
     * @return string
     */
    public function getLangcode()
    {
        return $this->langcode;
    }

    /**
     * Set userid
     *
     * @param \Application\Entity\LtUser $userid
     *
     * @return LtUserSecurityQuestion
     */
    public function setUserid(\Application\Entity\LtUser $userid)
    {
        $this->userid = $userid;
    
        return $this;
    }

    /**
     * Get userid
     *
     * @return \Application\Entity\LtUser
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
