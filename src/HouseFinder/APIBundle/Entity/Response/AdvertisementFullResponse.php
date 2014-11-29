<?php
namespace HouseFinder\APIBundle\Entity\Response;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class AdvertisementFullResponse extends Response
{
    /**
     * @Type("integer")
     */
    public $id;

    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $description;

    /**
     * @Type("integer")
     */
    public $price;

    /**
     * @Type("string")
     */
    public $currency;

    /**
     * @Type("string")
     */
    public $photo;

    /**
     * @Type("DateTime")
     * @SerializedName("lastDate")
     */
    public $lastDate;

    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\AddressResponse")
     */
    public $address;

    /**
     * @Type("string")
     */
    public $type;

    /**
     * @Type("string")
     * @SerializedName("rentType")
     */
    public $rentType;

    /**
     * @Type("DateTime")
     * @SerializedName("rentStartDate")
     */
    public $rentStartDate;

    /**
     * @Type("string")
     * @SerializedName("houseType")
     */
    public $houseType;

    /**
     * @Type("float")
     * @SerializedName("fullSpace")
     */
    public $fullSpace;

    /**
     * @Type("float")
     * @SerializedName("livingSpace")
     */
    public $livingSpace;

    /**
     * @Type("integer")
     */
    public $level;

    /**
     * @Type("integer")
     * @SerializedName("maxLevels")
     */
    public $maxLevels;

    /**
     * @Type("string")
     * @SerializedName("wallType")
     */
    public $wallType;

    /**
     * @Type("string")
     * @SerializedName("brickType")
     */
    public $brickType;

    /**
     * @Type("string")
     * @SerializedName("heatingType")
     */
    public $heatingType;

    /**
     * @Type("array")
     */
    public $special;

    /**
     * @Type("DateTime")
     */
    public $created;

    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\AdvertisementOwnerResponse")
     */
    public $owner;

    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\HouseResponse")
     */
    public $house;

    /**
     * @Type("array")
     */
    public $photos;

    /**
     * @Type("array")
     */
    public $rooms;

    /**
     * @Type("integer")
     * @SerializedName("roomsLiving")
     */
    public $roomsLiving;


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
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
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
     * @param mixed $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }

    /**
     * @return mixed
     */
    public function getHouse()
    {
        return $this->house;
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
     * @param mixed $lastDate
     */
    public function setLastDate($lastDate)
    {
        $this->lastDate = $lastDate;
    }

    /**
     * @return mixed
     */
    public function getLastDate()
    {
        return $this->lastDate;
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
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
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
     * @param mixed $rentStartDate
     */
    public function setRentStartDate($rentStartDate)
    {
        $this->rentStartDate = $rentStartDate;
    }

    /**
     * @return mixed
     */
    public function getRentStartDate()
    {
        return $this->rentStartDate;
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
     * @param mixed $roomsLiving
     */
    public function setRoomsLiving($roomsLiving)
    {
        $this->roomsLiving = $roomsLiving;
    }

    /**
     * @return mixed
     */
    public function getRoomsLiving()
    {
        return $this->roomsLiving;
    }

    /**
     * @param mixed $special
     */
    public function setSpecial($special)
    {
        $this->special = $special;
    }

    /**
     * @return mixed
     */
    public function getSpecial()
    {
        return $this->special;
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