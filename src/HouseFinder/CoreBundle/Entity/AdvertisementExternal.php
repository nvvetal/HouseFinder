<?php
namespace HouseFinder\CoreBundle\Entity;
use HouseFinder\CoreBundle\Entity\Advertisement;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class AdvertisementExternal extends Advertisement
{
    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceId;

    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceHash;



    /** @ORM\Column(type="text", nullable=true) */
    protected $sourceURL;

    /**
     * Set sourceId
     *
     * @param string $sourceId
     * @return AdvertisementExternal
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    
        return $this;
    }

    /**
     * Get sourceId
     *
     * @return string 
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set sourceHash
     *
     * @param string $sourceHash
     * @return AdvertisementExternal
     */
    public function setSourceHash($sourceHash)
    {
        $this->sourceHash = $sourceHash;
    
        return $this;
    }

    /**
     * Get sourceHash
     *
     * @return string 
     */
    public function getSourceHash()
    {
        return $this->sourceHash;
    }

    /**
     * Set sourceURL
     *
     * @param string $sourceURL
     * @return AdvertisementExternal
     */
    public function setSourceURL($sourceURL)
    {
        $this->sourceURL = $sourceURL;
    
        return $this;
    }

    /**
     * Get sourceURL
     *
     * @return string 
     */
    public function getSourceURL()
    {
        return $this->sourceURL;
    }
}