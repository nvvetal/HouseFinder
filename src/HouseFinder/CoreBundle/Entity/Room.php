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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Advertisement")
     * @ORM\JoinColumn
     */
    protected $advertisement;

    /** @ORM\Column(type="string") */
    protected $type = self::TYPE_ROOM;

    /** @ORM\Column(type="float") */
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
}
