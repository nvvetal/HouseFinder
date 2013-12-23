<?php
namespace HouseFinder\CoreBundle\Entity;
use HouseFinder\CoreBundle\Entity\Advertisement;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class ExternalAdvertisement extends Advertisement
{
    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceId;

    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceHash;



    /** @ORM\Column(type="text", nullable=true) */
    protected $sourceURL;

    /**
     * @param mixed $sourceId
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param mixed $sourceURL
     */
    public function setSourceURL($sourceURL)
    {
        $this->sourceURL = $sourceURL;
    }

    /**
     * @return mixed
     */
    public function getSourceURL()
    {
        return $this->sourceURL;
    }

    /**
     * @param mixed $sourceHash
     */
    public function setSourceHash($sourceHash)
    {
        $this->sourceHash = $sourceHash;
    }

    /**
     * @return mixed
     */
    public function getSourceHash()
    {
        return $this->sourceHash;
    }

}

