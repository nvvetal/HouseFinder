<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Organization
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uniq_organization", columns={"name","address_id"})})
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\OrganizationRepository")
 */
class Organization
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
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumn
     */
    protected $address;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="text", nullable=true) */
    protected $description;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

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
     * Set name
     *
     * @param string $name
     * @return Organization
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param array $description
     * @return Organization
     */
    public function setDescription(array $description = array())
    {
        $this->description = serialize($description);
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return is_null($this->description) ? array() : unserialize($this->description);
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Organization
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return Organization
     */
    public function setAddress(Address $address = null)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}