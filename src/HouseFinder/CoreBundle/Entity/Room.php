<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 * @ORM\Table()
 * @ORM\Entity
 */
class Room
{
    const TYPE_ROOM     = 'room';
    const TYPE_KITCHEN  = 'kitchen';
    const TYPE_BATHROOM = 'bathroom';
    const TYPE_WC       = 'wc';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Advertisement", inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $advertisement;

    /** @ORM\Column(type="string") */
    protected $type = self::TYPE_ROOM;

    /** @ORM\Column(type="float", nullable=true) */
    protected $space;



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
     * Set type
     *
     * @param string $type
     * @return Room
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set space
     *
     * @param float $space
     * @return Room
     */
    public function setSpace($space)
    {
        $this->space = $space;
    
        return $this;
    }

    /**
     * Get space
     *
     * @return float 
     */
    public function getSpace()
    {
        return $this->space;
    }

    /**
     * Set advertisement
     *
     * @param \HouseFinder\CoreBundle\Entity\Advertisement $advertisement
     * @return Room
     */
    public function setAdvertisement(\HouseFinder\CoreBundle\Entity\Advertisement $advertisement = null)
    {
        $this->advertisement = $advertisement;
    
        return $this;
    }

    /**
     * Get advertisement
     *
     * @return \HouseFinder\CoreBundle\Entity\Advertisement 
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }
}