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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Advertisement", inversedBy="photos")
     * @ORM\JoinColumn
     */
    protected $advertisement;

    //TODO: refactor to path (nginx + slando)
    /** @ORM\Column(type="text") */
    protected $url;

    /** @ORM\Column(type="string", nullable=true) */
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

    /**
     * Set url
     *
     * @param string $url
     * @return AdvertisementPhoto
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return AdvertisementPhoto
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set advertisement
     *
     * @param \HouseFinder\CoreBundle\Entity\Advertisement $advertisement
     * @return AdvertisementPhoto
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