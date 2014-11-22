<?php
namespace HouseFinder\ParserBundle\Entity;

use HouseFinder\CoreBundle\Entity\Room;

class BaseParserEntity
{
    /** @var string $url */
    protected $url;

    /** @var string $address */
    protected $address;

    /** @var string $name */
    protected $name;

    /** @var string $description */
    protected $description;

    /** @var integer $price */
    protected $price;

    /** @var string $currency */
    protected $currency;

    /** @var string $type */
    protected $type;

    /** @var string $rentType */
    protected $rentType;

    /** @var \DateTime $rentStartDate */
    protected $rentStartDate;

    /** @var array $rooms */
    protected $rooms = array();

    /** @var float $fullSpace */
    protected $fullSpace;

    /** @var float $livingSpace */
    protected $livingSpace;

    /** @var int $level */
    protected $level;

    /** @var int $maxLevels */
    protected $maxLevels;

    /** @var string $wallType */
    protected $wallType;

    /** @var string $houseType */
    protected $houseType;

    /** @var  array $photos */
    protected $photos;

    /** @var  array $params */
    protected $params;

    /** @var  array $phones */
    protected $phones;

    /** @var  \DateTime $createdDateTime */
    protected $createdDateTime;

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param float $fullSpace
     */
    public function setFullSpace($fullSpace)
    {
        $this->fullSpace = $fullSpace;
    }

    /**
     * @return float
     */
    public function getFullSpace()
    {
        return $this->fullSpace;
    }

    /**
     * @param string $houseType
     */
    public function setHouseType($houseType)
    {
        $this->houseType = $houseType;
    }

    /**
     * @return string
     */
    public function getHouseType()
    {
        return $this->houseType;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param float $livingSpace
     */
    public function setLivingSpace($livingSpace)
    {
        $this->livingSpace = $livingSpace;
    }

    /**
     * @return float
     */
    public function getLivingSpace()
    {
        return $this->livingSpace;
    }

    /**
     * @param int $maxLevels
     */
    public function setMaxLevels($maxLevels)
    {
        $this->maxLevels = $maxLevels;
    }

    /**
     * @return int
     */
    public function getMaxLevels()
    {
        return $this->maxLevels;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param \DateTime $rentStartDate
     */
    public function setRentStartDate($rentStartDate)
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

    /**
     * @param string $rentType
     */
    public function setRentType($rentType)
    {
        $this->rentType = $rentType;
    }

    /**
     * @return string
     */
    public function getRentType()
    {
        return $this->rentType;
    }

    /**
     * @param array $rooms
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * @return array
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $wallType
     */
    public function setWallType($wallType)
    {
        $this->wallType = $wallType;
    }

    /**
     * @return string
     */
    public function getWallType()
    {
        return $this->wallType;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param array $phones
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /**
     * @param \DateTime $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function addRoom(Room $room)
    {
        $this->rooms[] = $room;
    }
}