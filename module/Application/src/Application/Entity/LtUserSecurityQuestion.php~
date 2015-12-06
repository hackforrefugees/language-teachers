<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtUserSecurityQuestion
 *
 * @ORM\Table(name="lt_user_security_question", indexes={@ORM\Index(name="userSecurityQuestionId_idx", columns={"securityQuestionId"})})
 * @ORM\Entity
 */
class LtUserSecurityQuestion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="securityQuestionAnswer", type="string", length=200, nullable=true)
     */
    private $securityquestionanswer;

    /**
     * @var \Application\Entity\LtSecurityQuestion
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\LtSecurityQuestion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="securityQuestionId", referencedColumnName="securityQuestionId")
     * })
     */
    private $securityquestionid;



    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return LtUserSecurityQuestion
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
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
     * Set securityquestionid
     *
     * @param \Application\Entity\LtSecurityQuestion $securityquestionid
     *
     * @return LtUserSecurityQuestion
     */
    public function setSecurityquestionid(\Application\Entity\LtSecurityQuestion $securityquestionid)
    {
        $this->securityquestionid = $securityquestionid;

        return $this;
    }

    /**
     * Get securityquestionid
     *
     * @return \Application\Entity\LtSecurityQuestion
     */
    public function getSecurityquestionid()
    {
        return $this->securityquestionid;
    }
}
