<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 16.12.13
 * Time: 17:47
 */

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({"base" = "User", "internal" = "UserInternal", "slando" = "UserSlando"})
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements UserInterface
{
    const TYPE_PRIVATE = 'private';
    const TYPE_REALTOR = 'realtor';
    const TYPE_BUILDER = 'builder';
    const TYPE_BANK    = 'bank';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", nullable=true) */
    protected $vkontakteId;

    /** @ORM\Column(type="string", nullable=true) */
    protected $facebookId;

    /** @ORM\Column(type="string") */
    protected $type = self::TYPE_PRIVATE;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Email", mappedBy="user", cascade={"persist", "merge"})
     */
    protected $emails;

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set vkontakteId
     *
     * @param string $vkontakteId
     * @return User
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakteId = $vkontakteId;

        return $this;
    }

    /**
     * Get vkontakteId
     *
     * @return string
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateEmails()
    {
        $email = new Email();
        $email->setValue($this->getEmail());
        $this->addEmail($email);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->emails = new ArrayCollection();
    }

    /**
     * Add email
     *
     * @param Email $email
     * @return User
     */
    public function addEmail(Email $email)
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->setUser($this);
        }

        return $this;
    }

    /**
     * Add emails
     *
     * @param Collection $emails
     * @return User
     */
    public function addEmails(Collection $emails)
    {
        foreach($emails as $email) {
            $this->addEmail($email);
        }

        return $this;
    }

    /**
     * Remove email
     *
     * @param Email $email
     */
    public function removeEmail(Email $email)
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
            $email->setUser(null);
        }
    }


    /**
     * Get emails
     *
     * @return Collection
     */
    public function getEmails()
    {
        return $this->emails;
    }

    public function getUsernameFiltered()
    {
        $username = $this->getUsername();
        if(preg_match("/^([^\@]+)\@/i", $username, $m)) return $m[1];
        return NULL;
    }
}