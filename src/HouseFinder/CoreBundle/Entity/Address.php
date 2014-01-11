<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\AddressRepository")
 */
class Address
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected $region;

    /** @ORM\Column(type="string", nullable=true) */
    protected $locality;

    /** @ORM\Column(type="string", nullable=true) */
    protected $street;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $house;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set region
     *
     * @param string $region
     * @return Address
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set locality
     *
     * @param string $locality
     * @return Address
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    
        return $this;
    }

    /**
     * Get locality
     *
     * @return string 
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
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
     * Set house
     *
     * @param integer $house
     * @return Address
     */
    public function setHouse($house)
    {
        $this->house = $house;
    
        return $this;
    }

    /**
     * Get house
     *
     * @return integer 
     */
    public function getHouse()
    {
        return $this->house;
    }
}