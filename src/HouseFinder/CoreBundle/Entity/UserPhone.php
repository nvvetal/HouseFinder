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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn
     */
    protected $user;

    /** @ORM\Column(type="integer") */
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
}
