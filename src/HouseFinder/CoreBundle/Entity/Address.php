<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Advertisement", mappedBy="address")
     */
    protected $advertisements;

    //TODO: refactor to city -> street
    /** @ORM\Column(type="string") */
    protected $address;

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
        $this->advertisements = new ArrayCollection();
    }

    /**
     * Add advertisements
     *
     * @param Advertisement $advertisements
     * @return Address
     */
    public function addAdvertisement(Advertisement $advertisements)
    {
        $this->advertisements[] = $advertisements;

        return $this;
    }

    /**
     * Remove advertisements
     *
     * @param Advertisement $advertisements
     */
    public function removeAdvertisement(Advertisement $advertisements)
    {
        $this->advertisements->removeElement($advertisements);
    }

    /**
     * Get advertisements
     *
     * @return Collection
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