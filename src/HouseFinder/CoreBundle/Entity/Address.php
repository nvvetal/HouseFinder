<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity
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
    public $id;

    /**
     * @ORM\OneToMany(targetEntity="Advertisement", mappedBy="address")
     */
    public $advertisements;

    //TODO: refactor to city -> street
    /** @ORM\Column(type="string") */
    public $address;

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
     * Constructor
     */
    public function __construct()
    {
        $this->advertisements = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add advertisements
     *
     * @param \HouseFinder\CoreBundle\Entity\Advertisement $advertisements
     * @return Address
     */
    public function addAdvertisement(\HouseFinder\CoreBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements[] = $advertisements;
    
        return $this;
    }

    /**
     * Remove advertisements
     *
     * @param \HouseFinder\CoreBundle\Entity\Advertisement $advertisements
     */
    public function removeAdvertisement(\HouseFinder\CoreBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements->removeElement($advertisements);
    }

    /**
     * Get advertisements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
}