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
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HouseFinder\CoreBundle\Entity\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorMap({
 *      "base" = "User",
 *      "internal" = "UserInternal",
 *      "slando" = "UserSlando",
 *      "kvartira_zhitomir_ua" = "UserKvartiraZhitomirUa"
 * })
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
     * @ORM\OneToMany(targetEntity="Email", mappedBy="user", cascade={"all"})
     */
    protected $emails;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="UserPhone", mappedBy="user", cascade={"all"})
     */
    protected $phones;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var \DateTime $phoneUpdated
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $phoneUpdated;

    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceHash;

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
        $this->phones = new ArrayCollection();
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


    /**
     * Add phone
     *
     * @param UserPhone $phone
     * @return User
     */
    public function addPhone(UserPhone $phone)
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setUser($this);
        }

        return $this;
    }

    /**
     * Add phones
     *
     * @param Collection $phones
     * @return User
     */
    public function addPhones(Collection $phones)
    {
        foreach($phones as $phone) {
            $this->addPhone($phone);
        }

        return $this;
    }

    /**
     * Remove phone
     *
     * @param UserPhone $phone
     */
    public function removePhone(UserPhone $phone)
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
            $phone->setUser(null);
        }
    }


    /**
     * Get phones
     *
     * @return Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }



    public function getUsernameFiltered()
    {
        $username = $this->getUsername();
        if(preg_match("/^([^\@]+)\@/i", $username, $m)) return $m[1];
        return NULL;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getPhoneUpdated()
    {
        return $this->phoneUpdated;
    }

    /**
     * @param \DateTime $phoneUpdated
     */
    public function setPhoneUpdated($phoneUpdated)
    {
        $this->phoneUpdated = $phoneUpdated;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Set sourceHash
     *
     * @param string $sourceHash
     * @return UserSlando
     */
    public function setSourceHash($sourceHash)
    {
        $this->sourceHash = $sourceHash;

        return $this;
    }

    /**
     * Get sourceHash
     *
     * @return string
     */
    public function getSourceHash()
    {
        return $this->sourceHash;
    }
}