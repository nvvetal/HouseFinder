<?php

namespace HouseFinder\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({"base" = "Advertisement", "internal" = "AdvertisementInternal", "external" = "AdvertisementExternal","slando" = "AdvertisementSlando"})
 * @ORM\Entity
 */
class Advertisement
{

    const TYPE_SELL     = 'sell';
    const TYPE_BUY      = 'buy';
    const TYPE_RENT     = 'rent';

    const RENT_TYPE_HOUR    = 'hour';
    const RENT_TYPE_DAY     = 'day';
    const RENT_TYPE_LONG    = 'long';

    const HOUSE_TYPE_NEW = 'new';
    const HOUSE_TYPE_OLD = 'old';

    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_UAH = 'UAH';

    const WALL_TYPE_BRICK       = 'brick';
    const WALL_TYPE_PANEL       = 'panel';
    const WALL_TYPE_BLOCK       = 'block';
    const WALL_TYPE_MONOLITH    = 'monolith';
    const WALL_TYPE_WOOD        = 'wood';

    const BRICK_TYPE_WHITE  = 'white';
    const BRICK_TYPE_RED    = 'red';

    const HEATING_TYPE_CENTRAL      = 'central';
    const HEATING_TYPE_INDEPENDENT  = 'independent';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn
     */
    protected $user;

    /** @ORM\Column(type="string") */
    protected $type;

    /** @ORM\Column(type="string", nullable=true) */
    protected $rentType;

    /**
     * @var \DateTime $rentStartDate
     * @ORM\Column(type="date", nullable=true)
     */
    protected $rentStartDate;


    /** @ORM\Column(type="string", nullable=true) */
    protected $houseType;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="text") */
    protected $description;

    /** @ORM\Column(type="integer") */
    protected $price;

    /** @ORM\Column(type="string") */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="AdvertisementPhoto", mappedBy="advertisement", cascade={"all"})
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="advertisement", cascade={"all"})
     */
    protected $rooms;

    /** @ORM\Column(type="float", nullable=true) */
    protected $fullSpace;

    /** @ORM\Column(type="float", nullable=true) */
    protected $livingSpace;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected $level;

    /** @ORM\Column(type="smallint", nullable=true) */
    protected $maxLevels; //only if not house

    /** @ORM\Column(type="string", nullable=true) */
    protected $wallType;

    /** @ORM\Column(type="string", nullable=true) */
    protected $brickType;

    /**
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumn
     */
    protected $address;


    protected $haveGarage;
    protected $haveVault;

    /** @ORM\Column(type="string", nullable=true) */
    protected $heatingType;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var datetime $contentChanged
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"title", "body"})
     */
    protected $contentChanged;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rooms = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set type
     *
     * @param string $type
     * @return Advertisement
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set rentType
     *
     * @param string $rentType
     * @return Advertisement
     */
    public function setRentType($rentType)
    {
        $this->rentType = $rentType;
    
        return $this;
    }

    /**
     * Get rentType
     *
     * @return string 
     */
    public function getRentType()
    {
        return $this->rentType;
    }

    /**
     * Set houseType
     *
     * @param string $houseType
     * @return Advertisement
     */
    public function setHouseType($houseType)
    {
        $this->houseType = $houseType;
    
        return $this;
    }

    /**
     * Get houseType
     *
     * @return string 
     */
    public function getHouseType()
    {
        return $this->houseType;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Advertisement
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
     * Set description
     *
     * @param string $description
     * @return Advertisement
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Advertisement
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Advertisement
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set fullSpace
     *
     * @param float $fullSpace
     * @return Advertisement
     */
    public function setFullSpace($fullSpace)
    {
        $this->fullSpace = $fullSpace;
    
        return $this;
    }

    /**
     * Get fullSpace
     *
     * @return float 
     */
    public function getFullSpace()
    {
        return $this->fullSpace;
    }

    /**
     * Set livingSpace
     *
     * @param float $livingSpace
     * @return Advertisement
     */
    public function setLivingSpace($livingSpace)
    {
        $this->livingSpace = $livingSpace;
    
        return $this;
    }

    /**
     * Get livingSpace
     *
     * @return float 
     */
    public function getLivingSpace()
    {
        return $this->livingSpace;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Advertisement
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set maxLevels
     *
     * @param integer $maxLevels
     * @return Advertisement
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
     * @return Advertisement
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
     * @return Advertisement
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
     * Set heatingType
     *
     * @param string $heatingType
     * @return Advertisement
     */
    public function setHeatingType($heatingType)
    {
        $this->heatingType = $heatingType;
    
        return $this;
    }

    /**
     * Get heatingType
     *
     * @return string 
     */
    public function getHeatingType()
    {
        return $this->heatingType;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Advertisement
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Advertisement
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set contentChanged
     *
     * @param \DateTime $contentChanged
     * @return Advertisement
     */
    public function setContentChanged($contentChanged)
    {
        $this->contentChanged = $contentChanged;
    
        return $this;
    }

    /**
     * Get contentChanged
     *
     * @return \DateTime 
     */
    public function getContentChanged()
    {
        return $this->contentChanged;
    }

    /**
     * Set user
     *
     * @param \HouseFinder\CoreBundle\Entity\User $user
     * @return Advertisement
     */
    public function setUser(\HouseFinder\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \HouseFinder\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add photos
     *
     * @param \HouseFinder\CoreBundle\Entity\AdvertisementPhoto $photos
     * @return Advertisement
     */
    public function addPhoto(\HouseFinder\CoreBundle\Entity\AdvertisementPhoto $photos)
    {
        $this->photos[] = $photos;
    
        return $this;
    }

    /**
     * Remove photos
     *
     * @param \HouseFinder\CoreBundle\Entity\AdvertisementPhoto $photos
     */
    public function removePhoto(\HouseFinder\CoreBundle\Entity\AdvertisementPhoto $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add rooms
     *
     * @param \HouseFinder\CoreBundle\Entity\Room $rooms
     * @return Advertisement
     */
    public function addRoom(\HouseFinder\CoreBundle\Entity\Room $rooms)
    {
        $this->rooms[] = $rooms;
    
        return $this;
    }

    /**
     * Remove rooms
     *
     * @param \HouseFinder\CoreBundle\Entity\Room $rooms
     */
    public function removeRoom(\HouseFinder\CoreBundle\Entity\Room $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set address
     *
     * @param \HouseFinder\CoreBundle\Entity\Address $address
     * @return Advertisement
     */
    public function setAddress(\HouseFinder\CoreBundle\Entity\Address $address = null)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return \HouseFinder\CoreBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \DateTime $rentStartDate
     */
    public function setRentStartDate(\DateTime $rentStartDate)
    {
        $this->rentStartDate = $rentStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getRentStartDate()
    {
        return $this->rentStartDate;
    }

    public function getKitchens()
    {
        $kitchens = array();
        if(count($this->getRooms()) == 0) return NULL;
        $rooms = $this->getRooms();
        foreach($rooms as &$room)
        {
            /* @var $room Room */
            if($room->getType() != Room::TYPE_KITCHEN) continue;
            $kitchens[] = $room;
        }
        return $kitchens;
    }


}