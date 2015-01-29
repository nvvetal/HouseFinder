<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\CoreBundle\Entity\User;

/**
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({
 *      "base" = "Advertisement",
 *      "internal" = "AdvertisementInternal",
 *      "external" = "AdvertisementExternal",
 *      "slando" = "AdvertisementSlando",
 *      "kvartira_zhitomir_ua" = "AdvertisementKvartiraZhitomirUa"
 * })
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\AdvertisementRepository")
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

    const WALL_TYPE_ALL       = 'all';
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

    /**
     * @ORM\ManyToOne(targetEntity="House")
     * @ORM\JoinColumn
     */
    protected $house;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="text") */
    protected $description;

    /** @ORM\Column(type="integer") */
    protected $price;

    /** @ORM\Column(type="string") */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="AdvertisementPhoto", mappedBy="advertisement", cascade={"all"}, orphanRemoval=true)
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="advertisement", cascade={"all"}, orphanRemoval=true)
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

    /** @ORM\Column(type="string", nullable=true) */
    protected $heatingType;

    /** @ORM\Column(type="text", nullable=true) */
    protected $special;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var \DateTime $contentChanged
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"title", "body"})
     */
    protected $contentChanged;

    /**
     * @ORM\OneToMany(targetEntity="AdvertisementPublish", mappedBy="advertisement", cascade={"all"}, orphanRemoval=true)
     */
    protected $publishes;

    /** @ORM\Column(type="text", nullable=true) */
    protected $params;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->publishes = new ArrayCollection();
        $this->rooms = new ArrayCollection();
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
        $description = trim($this->description);
        return $description;
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
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        $lastUpdated = $this->getUpdated();
        $created = $this->getCreated();
        return !empty($lastUpdated) ?  $lastUpdated : $created;
    }


    /**
     * Set user
     *
     * @param User $user
     * @return Advertisement
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add photo
     *
     * @param AdvertisementPhoto $photo
     * @return Advertisement
     */
    public function addPhoto(AdvertisementPhoto $photo)
    {
        $photo->setAdvertisement($this);
        $this->photos[] = $photo;
    
        return $this;
    }

    /**
     * Remove photos
     *
     * @param AdvertisementPhoto $photos
     */
    public function removePhoto(AdvertisementPhoto $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add rooms
     *
     * @param Room $room
     * @return Advertisement
     */
    public function addRoom(Room $room)
    {
        $room->setAdvertisement($this);
        $this->rooms[] = $room;

        return $this;
    }

    /**
     * Remove rooms
     *
     * @param Room $rooms
     */
    public function removeRoom(Room $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return Collection
     */
    public function &getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return Advertisement
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

    public function &getKitchens()
    {
        $rooms = &$this->getRooms();
        $kitchens = array();
        if(count($rooms) == 0) return NULL;
        foreach($rooms as &$room)
        {
            /* @var $room Room */
            if($room->getType() != Room::TYPE_KITCHEN) continue;
            $kitchens[] = $room;
        }
        return $kitchens;
    }

    public function getLivingRooms()
    {
        $rooms = $this->getRooms();
        $lRooms = array();
        if($rooms->count() == 0) return NULL;
        foreach($rooms as &$room)
        {
            /* @var $room Room */
            if($room->getType() != Room::TYPE_ROOM) continue;
            $lRooms[] = $room;
        }
        return $lRooms;
    }

    public function setFirstKitchenSpace($space)
    {
        $kitchens = &$this->getKitchens();
        if(count($kitchens) == 0){
            $kitchen = new Room();
            $kitchen->setAdvertisement($this);
            $kitchen->setType(Room::TYPE_KITCHEN);
            $kitchen->setSpace($space);
            $this->addRoom($kitchen);
        }elseif(is_null($kitchens[0]->getSpace())){
            $kitchens[0]->setSpace($space);
        }
    }

    public function setFirstLivingRoomSpace($space)
    {
        $rooms = &$this->getLivingRooms();
        if(count($rooms) == 0){
            $room = new Room();
            $room->setAdvertisement($this);
            $room->setType(Room::TYPE_ROOM);
            $room->setSpace($space);
            $this->addRoom($room);
        }elseif(is_null($rooms[0]->getSpace())){
            $rooms[0]->setSpace($space);
        }
    }

    /**
     * @param $key
     * @param $val
     */
    public function setSpecial($key, $val)
    {
        $special = $this->getSpecial();
        $special[$key] = $val;
        $this->special = json_encode($special);
    }

    /**
     * @return array
     */
    public function getSpecial()
    {
        $special = $this->special;
        if(is_null($special)) return array();
        return json_decode($special, true);
    }

    /**
     * @return House
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param House $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }

    public function isRent()
    {
        return $this->getType() == Advertisement::TYPE_RENT;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return is_null($this->params) ? array() : json_decode($this->params, true);
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = json_encode($params);
    }
}