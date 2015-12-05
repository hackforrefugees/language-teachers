<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtOrganistation
 *
 * @ORM\Table(name="lt_organistation")
 * @ORM\Entity
 */
class LtOrganistation
{
    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonName", type="string", length=150, nullable=false)
     */
    private $contactpersonname;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonEmail", type="string", length=150, nullable=false)
     */
    private $contactpersonemail;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonPhone", type="string", length=20, nullable=true)
     */
    private $contactpersonphone;

    /**
     * @var \Application\Entity\LtUser
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\LtUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organistationId", referencedColumnName="userId")
     * })
     */
    private $organistationid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtEvent", inversedBy="organisationid")
     * @ORM\JoinTable(name="lt_organisation_creates_event",
     *   joinColumns={
     *     @ORM\JoinColumn(name="organisationId", referencedColumnName="organistationId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="eventId", referencedColumnName="eventId")
     *   }
     * )
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
     * Set contactpersonname
     *
     * @param string $contactpersonname
     *
     * @return LtOrganistation
     */
    public function setContactpersonname($contactpersonname)
    {
        $this->contactpersonname = $contactpersonname;

        return $this;
    }

    /**
     * Get contactpersonname
     *
     * @return string
     */
    public function getContactpersonname()
    {
        return $this->contactpersonname;
    }

    /**
     * Set contactpersonemail
     *
     * @param string $contactpersonemail
     *
     * @return LtOrganistation
     */
    public function setContactpersonemail($contactpersonemail)
    {
        $this->contactpersonemail = $contactpersonemail;

        return $this;
    }

    /**
     * Get contactpersonemail
     *
     * @return string
     */
    public function getContactpersonemail()
    {
        return $this->contactpersonemail;
    }

    /**
     * Set contactpersonphone
     *
     * @param string $contactpersonphone
     *
     * @return LtOrganistation
     */
    public function setContactpersonphone($contactpersonphone)
    {
        $this->contactpersonphone = $contactpersonphone;

        return $this;
    }

    /**
     * Get contactpersonphone
     *
     * @return string
     */
    public function getContactpersonphone()
    {
        return $this->contactpersonphone;
    }

    /**
     * Set organistationid
     *
     * @param \Application\Entity\LtUser $organistationid
     *
     * @return LtOrganistation
     */
    public function setOrganistationid(\Application\Entity\LtUser $organistationid)
    {
        $this->organistationid = $organistationid;

        return $this;
    }

    /**
     * Get organistationid
     *
     * @return \Application\Entity\LtUser
     */
    public function getOrganistationid()
    {
        return $this->organistationid;
    }

    /**
     * Add eventid
     *
     * @param \Application\Entity\LtEvent $eventid
     *
     * @return LtOrganistation
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
