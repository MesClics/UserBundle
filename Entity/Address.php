<?php

namespace MesClics\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="mesclics_address")
 * @ORM\Entity(repositoryClass="MesClics\UserBundle\Repository\AddressRepository")
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="c_o", type="string", length=255, nullable=true)
     */
    private $c_o;

    /**
     * @var string
     *
     * @ORM\Column(name="streetNumber", type="string", length=10, nullable=true)
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="streetName", type="string", length=255)
     */
    private $streetName;

    /**
     * @var string
     *
     * @ORM\Column(name="streetOther", type="string", length=255, nullable=true)
     */
    private $streetOther;

    /**
     * @var string
     *
     * @ORM\Column(name="zipCode", type="string", length=10)
     */
    private $zipCode;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(name="regionalInfo", type="string", length=255, nullable=true)
     */
    private $regionalInfo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cO
     *
     * @param string $cO
     *
     * @return Address
     */
    public function setCO($cO)
    {
        $this->cO = $cO;

        return $this;
    }

    /**
     * Get cO
     *
     * @return string
     */
    public function getCO()
    {
        return $this->cO;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return Address
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     *
     * @return Address
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get streetName
     *
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Set streetOther
     *
     * @param string $streetOther
     *
     * @return Address
     */
    public function setStreetOther($streetOther)
    {
        $this->streetOther = $streetOther;

        return $this;
    }

    /**
     * Get streetOther
     *
     * @return string
     */
    public function getStreetOther()
    {
        return $this->streetOther;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Address
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
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
     * @return Address
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
     * Set regionalInfo
     *
     * @param string $regionalInfo
     *
     * @return Address
     */
    public function setRegionalInfo($regionalInfo)
    {
        $this->regionalInfo = $regionalInfo;

        return $this;
    }

    /**
     * Get regionalInfo
     *
     * @return string
     */
    public function getRegionalInfo()
    {
        return $this->regionalInfo;
    }
}
