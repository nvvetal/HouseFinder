<?php
namespace HouseFinder\ParserBundle\Entity\KvartiraZhitomirUa;

use HouseFinder\ParserBundle\Entity\BaseParserEntity;

class KvartiraZhitomirUaParserEntity extends BaseParserEntity
{
    /** @var string $ownerName */
    protected $ownerName;

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