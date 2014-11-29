<?php
namespace HouseFinder\APIBundle\Entity\Response;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class HouseResponse extends Response
{

    /**
     * @Type("integer")
     */
    public $id;

    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\AddressResponse")
     */
    public $address;

    /**
     * @Type("string")
     * @SerializedName("brickType")
     */
    public $brickType;

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