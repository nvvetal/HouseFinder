<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HouseFinder\CoreBundle\Entity\Advertisement;

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

    /** @ORM\Column(type="string", nullable=true) */
    protected $ext;

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
     * @param Advertisement $advertisement
     * @return AdvertisementPhoto
     */
    public function setAdvertisement(Advertisement $advertisement = null)
    {
        $this->advertisement = $advertisement;
    
        return $this;
    }

    /**
     * Get advertisement
     *
     * @return Advertisement
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }


    /**
     * @param mixed $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }
}