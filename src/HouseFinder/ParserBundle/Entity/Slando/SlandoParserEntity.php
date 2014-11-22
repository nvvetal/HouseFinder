<?php
namespace HouseFinder\ParserBundle\Entity\Slando;

use HouseFinder\ParserBundle\Entity\BaseParserEntity;

class SlandoParserEntity extends BaseParserEntity
{
    /** @var string $ownerId */
    protected $ownerId;

    /** @var string $ownerHash */
    protected $ownerHash;

    /** @var string $ownerName */
    protected $ownerName;

    /** @var string $ownerUrl */
    protected $ownerUrl;

    /** @var string $ownerType */
    protected $ownerType;


    /** @var string $sourceId */
    protected $sourceId;

    /** @var string $sourceHash */
    protected $sourceHash;

    /** @var string $sourceUrl */
    protected $sourceURL;

    /**
     * @param string $sourceHash
     */
    public function setSourceHash($sourceHash)
    {
        $this->sourceHash = $sourceHash;
    }

    /**
     * @return string
     */
    public function getSourceHash()
    {
        return $this->sourceHash;
    }

    /**
     * @param string $sourceId
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceURL
     */
    public function setSourceURL($sourceURL)
    {
        $this->sourceURL = $sourceURL;
    }

    /**
     * @return string
     */
    public function getSourceURL()
    {
        return $this->sourceURL;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return string
     */
    public function getOwnerHash()
    {
        return $this->ownerHash;
    }

    /**
     * @param string $ownerHash
     */
    public function setOwnerHash($ownerHash)
    {
        $this->ownerHash = $ownerHash;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * @param string $ownerName
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }

    /**
     * @return string
     */
    public function getOwnerUrl()
    {
        return $this->ownerUrl;
    }

    /**
     * @param string $ownerUrl
     */
    public function setOwnerUrl($ownerUrl)
    {
        $this->ownerUrl = $ownerUrl;
    }

    /**
     * @return string
     */
    public function getOwnerType()
    {
        return $this->ownerType;
    }

    /**
     * @param string $ownerType
     */
    public function setOwnerType($ownerType)
    {
        $this->ownerType = $ownerType;
    }

}