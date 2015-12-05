<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtEvent
 *
 * @ORM\Table(name="lt_event", indexes={@ORM\Index(name="eventLanguage_idx", columns={"eventLanguage"})})
 * @ORM\Entity
 */
class LtEvent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="eventId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventTime", type="datetime", nullable=false)
     */
    private $eventtime;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxTeachers", type="integer", nullable=false)
     */
    private $maxteachers;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxStudents", type="integer", nullable=false)
     */
    private $maxstudents;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=150, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="streetNumber", type="string", length=5, nullable=false)
     */
    private $streetnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="zipCode", type="string", length=10, nullable=false)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=150, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=150, nullable=false)
     */
    private $country;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="eventTitle", type="string", length=200, nullable=true)
     */
    private $eventtitle;

    /**
     * @var \Application\Entity\LtLanguage
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\LtLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eventLanguage", referencedColumnName="langCode")
     * })
     */
    private $eventlanguage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtOrganistation", mappedBy="eventid")
     */
    private $organisationid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtStudent", inversedBy="eventid")
     * @ORM\JoinTable(name="lt_student_participates_in_event",
     *   joinColumns={
     *     @ORM\JoinColumn(name="eventId", referencedColumnName="eventId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="studentId", referencedColumnName="studentId")
     *   }
     * )
     */
    private $studentid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtVolunteer", mappedBy="eventcreateid")
     */
    private $volunteercreateid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\LtVolunteer", inversedBy="eventid")
     * @ORM\JoinTable(name="lt_volunteer_participates_in_event",
     *   joinColumns={
     *     @ORM\JoinColumn(name="eventId", referencedColumnName="eventId")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="volunteerId", referencedColumnName="volunteerId")
     *   }
     * )
     */
    private $volunteerid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organisationid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->studentid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->volunteercreateid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->volunteerid = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get eventid
     *
     * @return integer
     */
    public function getEventid()
    {
        return $this->eventid;
    }

    /**
     * Set eventtime
     *
     * @param \DateTime $eventtime
     *
     * @return LtEvent
     */
    public function setEventtime($eventtime)
    {
        $this->eventtime = $eventtime;

        return $this;
    }

    /**
     * Get eventtime
     *
     * @return \DateTime
     */
    public function getEventtime()
    {
        return $this->eventtime;
    }

    /**
     * Set maxteachers
     *
     * @param integer $maxteachers
     *
     * @return LtEvent
     */
    public function setMaxteachers($maxteachers)
    {
        $this->maxteachers = $maxteachers;

        return $this;
    }

    /**
     * Get maxteachers
     *
     * @return integer
     */
    public function getMaxteachers()
    {
        return $this->maxteachers;
    }

    /**
     * Set maxstudents
     *
     * @param integer $maxstudents
     *
     * @return LtEvent
     */
    public function setMaxstudents($maxstudents)
    {
        $this->maxstudents = $maxstudents;

        return $this;
    }

    /**
     * Get maxstudents
     *
     * @return integer
     */
    public function getMaxstudents()
    {
        return $this->maxstudents;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return LtEvent
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set streetnumber
     *
     * @param string $streetnumber
     *
     * @return LtEvent
     */
    public function setStreetnumber($streetnumber)
    {
        $this->streetnumber = $streetnumber;

        return $this;
    }

    /**
     * Get streetnumber
     *
     * @return string
     */
    public function getStreetnumber()
    {
        return $this->streetnumber;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return LtEvent
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return LtEvent
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return LtEvent
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return LtEvent
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
     * @return LtEvent
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

    /**
     * Set eventtitle
     *
     * @param string $eventtitle
     *
     * @return LtEvent
     */
    public function setEventtitle($eventtitle)
    {
        $this->eventtitle = $eventtitle;

        return $this;
    }

    /**
     * Get eventtitle
     *
     * @return string
     */
    public function getEventtitle()
    {
        return $this->eventtitle;
    }

    /**
     * Set eventlanguage
     *
     * @param \Application\Entity\LtLanguage $eventlanguage
     *
     * @return LtEvent
     */
    public function setEventlanguage(\Application\Entity\LtLanguage $eventlanguage = null)
    {
        $this->eventlanguage = $eventlanguage;

        return $this;
    }

    /**
     * Get eventlanguage
     *
     * @return \Application\Entity\LtLanguage
     */
    public function getEventlanguage()
    {
        return $this->eventlanguage;
    }

    /**
     * Add organisationid
     *
     * @param \Application\Entity\LtOrganistation $organisationid
     *
     * @return LtEvent
     */
    public function addOrganisationid(\Application\Entity\LtOrganistation $organisationid)
    {
        $this->organisationid[] = $organisationid;

        return $this;
    }

    /**
     * Remove organisationid
     *
     * @param \Application\Entity\LtOrganistation $organisationid
     */
    public function removeOrganisationid(\Application\Entity\LtOrganistation $organisationid)
    {
        $this->organisationid->removeElement($organisationid);
    }

    /**
     * Get organisationid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationid()
    {
        return $this->organisationid;
    }

    /**
     * Add studentid
     *
     * @param \Application\Entity\LtStudent $studentid
     *
     * @return LtEvent
     */
    public function addStudentid(\Application\Entity\LtStudent $studentid)
    {
        $this->studentid[] = $studentid;

        return $this;
    }

    /**
     * Remove studentid
     *
     * @param \Application\Entity\LtStudent $studentid
     */
    public function removeStudentid(\Application\Entity\LtStudent $studentid)
    {
        $this->studentid->removeElement($studentid);
    }

    /**
     * Get studentid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudentid()
    {
        return $this->studentid;
    }

    /**
     * Add volunteercreateid
     *
     * @param \Application\Entity\LtVolunteer $volunteercreateid
     *
     * @return LtEvent
     */
    public function addVolunteercreateid(\Application\Entity\LtVolunteer $volunteercreateid)
    {
        $this->volunteercreateid[] = $volunteercreateid;

        return $this;
    }

    /**
     * Remove volunteercreateid
     *
     * @param \Application\Entity\LtVolunteer $volunteercreateid
     */
    public function removeVolunteercreateid(\Application\Entity\LtVolunteer $volunteercreateid)
    {
        $this->volunteercreateid->removeElement($volunteercreateid);
    }

    /**
     * Get volunteercreateid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolunteercreateid()
    {
        return $this->volunteercreateid;
    }

    /**
     * Add volunteerid
     *
     * @param \Application\Entity\LtVolunteer $volunteerid
     *
     * @return LtEvent
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
