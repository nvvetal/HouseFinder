<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhone
 * @ORM\Table()
 * @ORM\Entity
 */
class UserPhone
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="phones")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /** @ORM\Column(type="string") */
    protected $msisdn;



    /** @ORM\Column(type="boolean") */
    protected $checked = false;



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
     * Set msisdn
     *
     * @param integer $msisdn
     * @return UserPhone
     */
    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    
        return $this;
    }

    /**
     * Get msisdn
     *
     * @return string
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return UserPhone
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set user
     *
     * @param \HouseFinder\CoreBundle\Entity\User $user
     * @return UserPhone
     */
    public function setUser(\HouseFinder\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \HouseFinder\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}