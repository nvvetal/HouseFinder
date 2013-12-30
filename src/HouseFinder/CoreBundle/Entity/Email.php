<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 * @ORM\Table()
 * @ORM\Entity
 */
class Email
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
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="emails")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $value;

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
     * Set user
     *
     * @param User $user
     * @return Email
     */
    public function setUser(User $user = null)
    {
        if ($user !== $this->getUser() && null !== $this->getUser()) {
            $this->getUser()->removeEmail($this);
        }
        if (null !== $user) {
            $user->addEmail($this);
        }
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Email
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}