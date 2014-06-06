<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Issue
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uniq_issue", columns={"organization_id","house_id","document_number"})})
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({"base" = "Issue", "komunal" = "IssueKomunal"})
 *
 */
class Issue
{

    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    const TYPE_TREE         = 'tree';
    const TYPE_CANALIZATION = 'canalization';
    const TYPE_WATER        = 'water';
    const TYPE_LIGHT        = 'light';
    const TYPE_GAS          = 'gas';
    const TYPE_TECH          = 'tech';
    const TYPE_OTHER        = 'other';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string") */
    protected $documentNumber;

    /**
     * @ORM\ManyToOne(targetEntity="House")
     * @ORM\JoinColumn
     */
    protected $house;

    /**
     * @ORM\ManyToOne(targetEntity="Organization")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $organization;

    /** @ORM\Column(type="string") */
    protected $type;

    /** @ORM\Column(type="string") */
    protected $typeDescription;

    /** @ORM\Column(type="text") */
    protected $description;

    /** @ORM\Column(type="string") */
    protected $priority;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
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
     * Set organization
     *
     * @param Organization $organization
     * @return Issue
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    
        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
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
     * Set house
     *
     * @param House $house
     * @return Issue
     */
    public function setHouse(House $house = null)
    {
        $this->house = $house;
    
        return $this;
    }

    /**
     * Get house
     *
     * @return House
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set documentNumber
     *
     * @param string $documentNumber
     * @return Issue
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;
    
        return $this;
    }

    /**
     * Get documentNumber
     *
     * @return string 
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Issue
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
     * Set typeDescription
     *
     * @param string $typeDescription
     * @return Issue
     */
    public function setTypeDescription($typeDescription)
    {
        $this->typeDescription = $typeDescription;
    
        return $this;
    }

    /**
     * Get typeDescription
     *
     * @return string 
     */
    public function getTypeDescription()
    {
        return $this->typeDescription;
    }
}