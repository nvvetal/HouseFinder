<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HouseFinder\CoreBundle\Entity\Address;

/**
 * House
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class House
{
    const WALL_TYPE_BRICK       = 'brick';
    const WALL_TYPE_PANEL       = 'panel';
    const WALL_TYPE_BLOCK       = 'block';
    const WALL_TYPE_MONOLITH    = 'monolith';
    const WALL_TYPE_WOOD        = 'wood';

    const BRICK_TYPE_WHITE  = 'white';
    const BRICK_TYPE_RED    = 'red';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"all"})
     */
    protected $address;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected $maxLevels;

    /** @ORM\Column(type="string", nullable=true) */
    protected $wallType;

    /** @ORM\Column(type="string", nullable=true) */
    protected $brickType;

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
     * Set maxLevels
     *
     * @param integer $maxLevels
     * @return House
     */
    public function setMaxLevels($maxLevels)
    {
        $this->maxLevels = $maxLevels;
    
        return $this;
    }

    /**
     * Get maxLevels
     *
     * @return integer 
     */
    public function getMaxLevels()
    {
        return $this->maxLevels;
    }

    /**
     * Set wallType
     *
     * @param string $wallType
     * @return House
     */
    public function setWallType($wallType)
    {
        $this->wallType = $wallType;
    
        return $this;
    }

    /**
     * Get wallType
     *
     * @return string 
     */
    public function getWallType()
    {
        return $this->wallType;
    }

    /**
     * Set brickType
     *
     * @param string $brickType
     * @return House
     */
    public function setBrickType($brickType)
    {
        $this->brickType = $brickType;
    
        return $this;
    }

    /**
     * Get brickType
     *
     * @return string 
     */
    public function getBrickType()
    {
        return $this->brickType;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return House
     */
    public function setAddress(Address $address = null)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}