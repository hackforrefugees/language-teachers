<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtVolunteer
 *
 * @ORM\Table(name="lt_volunteer", indexes={@ORM\Index(name="volunteerNativeLanguage_idx", columns={"nativeLanguage"})})
 * @ORM\Entity
 */
class LtVolunteer
{
    /**
     * @var \Application\Entity\LtLanguage
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\LtLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nativeLanguage", referencedColumnName="langCode")
     * })
     */
    private $nativelanguage;

    /**
     * @var \Application\Entity\LtUser
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\LtUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="volunteerId", referencedColumnName="userId")
     * })
     */
    private $volunteerid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtLanguage", inversedBy="volunteerid")
     * @ORM\JoinTable(name="lt_volunteer_knows_languages",
     *   joinColumns={
     *     @ORM\JoinColumn(name="volunteerId", referencedColumnName="volunteerId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="langCode", referencedColumnName="langCode")
     *   }
     * )
     */
    private $langcode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtEvent", mappedBy="volunteerid")
     */
    private $eventid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->langcode = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventid = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set nativelanguage
     *
     * @param \Application\Entity\LtLanguage $nativelanguage
     *
     * @return LtVolunteer
     */
    public function setNativelanguage(\Application\Entity\LtLanguage $nativelanguage = null)
    {
        $this->nativelanguage = $nativelanguage;
    
        return $this;
    }

    /**
     * Get nativelanguage
     *
     * @return \Application\Entity\LtLanguage
     */
    public function getNativelanguage()
    {
        return $this->nativelanguage;
    }

    /**
     * Set volunteerid
     *
     * @param \Application\Entity\LtUser $volunteerid
     *
     * @return LtVolunteer
     */
    public function setVolunteerid(\Application\Entity\LtUser $volunteerid)
    {
        $this->volunteerid = $volunteerid;
    
        return $this;
    }

    /**
     * Get volunteerid
     *
     * @return \Application\Entity\LtUser
     */
    public function getVolunteerid()
    {
        return $this->volunteerid;
    }

    /**
     * Add langcode
     *
     * @param \Application\Entity\LtLanguage $langcode
     *
     * @return LtVolunteer
     */
    public function addLangcode(\Application\Entity\LtLanguage $langcode)
    {
        $this->langcode[] = $langcode;
    
        return $this;
    }

    /**
     * Remove langcode
     *
     * @param \Application\Entity\LtLanguage $langcode
     */
    public function removeLangcode(\Application\Entity\LtLanguage $langcode)
    {
        $this->langcode->removeElement($langcode);
    }

    /**
     * Get langcode
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLangcode()
    {
        return $this->langcode;
    }

    /**
     * Add eventid
     *
     * @param \Application\Entity\LtEvent $eventid
     *
     * @return LtVolunteer
     */
    public function addEventid(\Application\Entity\LtEvent $eventid)
    {
        $this->eventid[] = $eventid;
    
        return $this;
    }

    /**
     * Remove eventid
     *
     * @param \Application\Entity\LtEvent $eventid
     */
    public function removeEventid(\Application\Entity\LtEvent $eventid)
    {
        $this->eventid->removeElement($eventid);
    }

    /**
     * Get eventid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventid()
    {
        return $this->eventid;
    }
}
