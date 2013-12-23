<?php
namespace HouseFinder\CoreBundle\Entity;

use HouseFinder\CoreBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class SlandoUser extends User
{
    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceHash;

    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceURL;

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

}
