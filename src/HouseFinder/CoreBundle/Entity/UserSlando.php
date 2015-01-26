<?php
namespace HouseFinder\CoreBundle\Entity;

use HouseFinder\CoreBundle\Entity\User as User;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\UserSlandoRepository")
 */
class UserSlando extends User
{
    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceURL;

     /**
     * Set sourceURL
     *
     * @param string $sourceURL
     * @return UserSlando
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