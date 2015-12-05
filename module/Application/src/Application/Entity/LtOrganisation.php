<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LtOrganisation
 *
 * @ORM\Table(name="lt_organisation")
 * @ORM\Entity
 */
class LtOrganisation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="organisationId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $organisationid;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonName", type="string", length=150, nullable=false)
     */
    private $contactpersonname;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonEmail", type="string", length=300, nullable=false)
     */
    private $contactpersonemail;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPersonPhone", type="string", length=20, nullable=true)
     */
    private $contactpersonphone;

    /**
     * @var string
     *
     * @ORM\Column(name="organisationDescription", type="text", length=65535, nullable=true)
     */
    private $organisationdescription;

    /**
     * @var string
     *
     * @ORM\Column(name="organisationWebsite", type="string", length=250, nullable=true)
     */
    private $organisationwebsite;



    /**
     * Get organisationid
     *
     * @return integer
     */
    public function getOrganisationid()
    {
        return $this->organisationid;
    }

    /**
     * Set contactpersonname
     *
     * @param string $contactpersonname
     *
     * @return LtOrganisation
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
     * @return LtOrganisation
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
     * @return LtOrganisation
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
     * Set organisationdescription
     *
     * @param string $organisationdescription
     *
     * @return LtOrganisation
     */
    public function setOrganisationdescription($organisationdescription)
    {
        $this->organisationdescription = $organisationdescription;

        return $this;
    }

    /**
     * Get organisationdescription
     *
     * @return string
     */
    public function getOrganisationdescription()
    {
        return $this->organisationdescription;
    }

    /**
     * Set organisationwebsite
     *
     * @param string $organisationwebsite
     *
     * @return LtOrganisation
     */
    public function setOrganisationwebsite($organisationwebsite)
    {
        $this->organisationwebsite = $organisationwebsite;

        return $this;
    }

    /**
     * Get organisationwebsite
     *
     * @return string
     */
    public function getOrganisationwebsite()
    {
        return $this->organisationwebsite;
    }
}
