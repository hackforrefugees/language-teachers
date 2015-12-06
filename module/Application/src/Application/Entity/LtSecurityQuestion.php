<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtSecurityQuestion
 *
 * @ORM\Table(name="lt_security_question", indexes={@ORM\Index(name="securityQuestionLangCode_idx", columns={"langCode"})})
 * @ORM\Entity
 */
class LtSecurityQuestion
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
     * @ORM\Column(name="securityQuestion", type="string", length=200, nullable=true)
     */
    private $securityquestion;

    /**
     * @var \Application\Entity\LtLanguage
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\LtLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="langCode", referencedColumnName="langCode")
     * })
     */
    private $langcode;



    /**
     * Set securityquestionid
     *
     * @param integer $securityquestionid
     *
     * @return LtSecurityQuestion
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
     * Set securityquestion
     *
     * @param string $securityquestion
     *
     * @return LtSecurityQuestion
     */
    public function setSecurityquestion($securityquestion)
    {
        $this->securityquestion = $securityquestion;

        return $this;
    }

    /**
     * Get securityquestion
     *
     * @return string
     */
    public function getSecurityquestion()
    {
        return $this->securityquestion;
    }

    /**
     * Set langcode
     *
     * @param \Application\Entity\LtLanguage $langcode
     *
     * @return LtSecurityQuestion
     */
    public function setLangcode(\Application\Entity\LtLanguage $langcode)
    {
        $this->langcode = $langcode;

        return $this;
    }

    /**
     * Get langcode
     *
     * @return \Application\Entity\LtLanguage
     */
    public function getLangcode()
    {
        return $this->langcode;
    }
}
