<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 16.12.13
 * Time: 17:47
 */

namespace HouseFinder\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({"base" = "User", "internal" = "UserInternal", "slando" = "UserSlando"})
 */
class User extends BaseUser
{
    const TYPE_PRIVATE = 'private';
    const TYPE_REALTOR = 'realtor';
    const TYPE_BUILDER = 'builder';
    const TYPE_BANK    = 'bank';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected $vkontakteId;

    /** @ORM\Column(type="string", nullable=true) */
    protected $facebookId;

    /** @ORM\Column(type="string") */
    protected $type = self::TYPE_PRIVATE;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set vkontakteId
     *
     * @param string $vkontakteId
     * @return User
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakteId = $vkontakteId;
    
        return $this;
    }

    /**
     * Get vkontakteId
     *
     * @return string 
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    
        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }
}