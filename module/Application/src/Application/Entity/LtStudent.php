<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtStudent
 *
 * @ORM\Table(name="lt_student", indexes={@ORM\Index(name="studentNativeLanguage_idx", columns={"nativeLanguage"})})
 * @ORM\Entity
 */
class LtStudent
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
     *   @ORM\JoinColumn(name="studentId", referencedColumnName="userId")
     * })
     */
    private $studentid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtEvent", mappedBy="studentid")
     */
    private $eventid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventid = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set nativelanguage
     *
     * @param \Application\Entity\LtLanguage $nativelanguage
     *
     * @return LtStudent
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
     * Set studentid
     *
     * @param \Application\Entity\LtUser $studentid
     *
     * @return LtStudent
     */
    public function setStudentid(\Application\Entity\LtUser $studentid)
    {
        $this->studentid = $studentid;
    
        return $this;
    }

    /**
     * Get studentid
     *
     * @return \Application\Entity\LtUser
     */
    public function getStudentid()
    {
        return $this->studentid;
    }

    /**
     * Add eventid
     *
     * @param \Application\Entity\LtEvent $eventid
     *
     * @return LtStudent
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
