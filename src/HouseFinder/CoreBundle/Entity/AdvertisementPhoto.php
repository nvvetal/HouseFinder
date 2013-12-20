<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvertisementPhoto
 * @ORM\Table()
 * @ORM\Entity
 */
class AdvertisementPhoto
{
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

    //TODO: refactor to path (nginx + slando)
    /** @ORM\Column(type="text") */
    protected $url;

    /** @ORM\Column(type="string") */
    protected $path;

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
