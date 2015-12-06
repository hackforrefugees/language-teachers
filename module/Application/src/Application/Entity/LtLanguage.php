<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtLanguage
 *
 * @ORM\Table(name="lt_language")
 * @ORM\Entity
 */
class LtLanguage
{
    /**
     * @var string
     *
     * @ORM\Column(name="langCode", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $langcode;

    /**
     * @var string
     *
     * @ORM\Column(name="languageName", type="string", length=150, nullable=true)
     */
    private $languagename;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtVolunteer", mappedBy="langcode")
     */
    private $volunteerid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volunteerid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set languagename
     *
     * @param string $languagename
     *
     * @return LtLanguage
     */
    public function setLanguagename($languagename)
    {
        $this->languagename = $languagename;

        return $this;
    }

    /**
     * Get languagename
     *
     * @return string
     */
    public function getLanguagename()
    {
        return $this->languagename;
    }

    /**
     * Add volunteerid
     *
     * @param \Application\Entity\LtVolunteer $volunteerid
     *
     * @return LtLanguage
     */
    public function addVolunteerid(\Application\Entity\LtVolunteer $volunteerid)
    {
        $this->volunteerid[] = $volunteerid;

        return $this;
    }

    /**
     * Remove volunteerid
     *
     * @param \Application\Entity\LtVolunteer $volunteerid
     */
    public function removeVolunteerid(\Application\Entity\LtVolunteer $volunteerid)
    {
        $this->volunteerid->removeElement($volunteerid);
    }

    /**
     * Get volunteerid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolunteerid()
    {
        return $this->volunteerid;
    }
}
