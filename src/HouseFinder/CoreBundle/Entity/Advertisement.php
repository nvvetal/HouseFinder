<?php

namespace HouseFinder\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({"base" = "Advertisement", "internal" = "InternalAdvertisement", "external" = "ExternalAdvertisement","slando" = "SlandoAdvertisement"})
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

    /** @ORM\Column(type="string") */
    protected $rentType;

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
     * @ORM\OneToMany(targetEntity="AdvertisementPhoto", mappedBy="advertisement")
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="advertisement")
     */
    protected $rooms;

    /** @ORM\Column(type="float") */
    protected $fullSpace;

    /** @ORM\Column(type="float") */
    protected $livingSpace;

    /** @ORM\Column(type="smallint") */
    protected $level;

    /** @ORM\Column(type="smallint") */
    protected $maxLevels; //only if not house

    /** @ORM\Column(type="string", nullable=true) */
    protected $wallType;

    /** @ORM\Column(type="string", nullable=true) */
    protected $brickType;

    /**
     * @ORM\OneToOne(targetEntity="Address")
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
     * @ORM\Column(type="datetime")
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
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $brickType
     */
    public function setBrickType($brickType)
    {
        $this->brickType = $brickType;
    }

    /**
     * @return mixed
     */
    public function getBrickType()
    {
        return $this->brickType;
    }

    /**
     * @param \HouseFinder\CoreBundle\Entity\datetime $contentChanged
     */
    public function setContentChanged($contentChanged)
    {
        $this->contentChanged = $contentChanged;
    }

    /**
     * @return \HouseFinder\CoreBundle\Entity\datetime
     */
    public function getContentChanged()
    {
        return $this->contentChanged;
    }

    /**
     * @param \HouseFinder\CoreBundle\Entity\datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \HouseFinder\CoreBundle\Entity\datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $fullSpace
     */
    public function setFullSpace($fullSpace)
    {
        $this->fullSpace = $fullSpace;
    }

    /**
     * @return mixed
     */
    public function getFullSpace()
    {
        return $this->fullSpace;
    }

    /**
     * @param mixed $haveGarage
     */
    public function setHaveGarage($haveGarage)
    {
        $this->haveGarage = $haveGarage;
    }

    /**
     * @return mixed
     */
    public function getHaveGarage()
    {
        return $this->haveGarage;
    }

    /**
     * @param mixed $haveVault
     */
    public function setHaveVault($haveVault)
    {
        $this->haveVault = $haveVault;
    }

    /**
     * @return mixed
     */
    public function getHaveVault()
    {
        return $this->haveVault;
    }

    /**
     * @param mixed $heatingType
     */
    public function setHeatingType($heatingType)
    {
        $this->heatingType = $heatingType;
    }

    /**
     * @return mixed
     */
    public function getHeatingType()
    {
        return $this->heatingType;
    }

    /**
     * @param mixed $houseType
     */
    public function setHouseType($houseType)
    {
        $this->houseType = $houseType;
    }

    /**
     * @return mixed
     */
    public function getHouseType()
    {
        return $this->houseType;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $livingSpace
     */
    public function setLivingSpace($livingSpace)
    {
        $this->livingSpace = $livingSpace;
    }

    /**
     * @return mixed
     */
    public function getLivingSpace()
    {
        return $this->livingSpace;
    }

    /**
     * @param mixed $maxLevels
     */
    public function setMaxLevels($maxLevels)
    {
        $this->maxLevels = $maxLevels;
    }

    /**
     * @return mixed
     */
    public function getMaxLevels()
    {
        return $this->maxLevels;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $rentType
     */
    public function setRentType($rentType)
    {
        $this->rentType = $rentType;
    }

    /**
     * @return mixed
     */
    public function getRentType()
    {
        return $this->rentType;
    }

    /**
     * @param mixed $rooms
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * @return mixed
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \HouseFinder\CoreBundle\Entity\datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \HouseFinder\CoreBundle\Entity\datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $wallType
     */
    public function setWallType($wallType)
    {
        $this->wallType = $wallType;
    }

    /**
     * @return mixed
     */
    public function getWallType()
    {
        return $this->wallType;
    }

}