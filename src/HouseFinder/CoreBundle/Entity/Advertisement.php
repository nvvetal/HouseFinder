<?php

namespace HouseFinder\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Advertisement
{
    const SOURCE_TYPE_INTERNAL  = 'internal';
    const SOURCE_TYPE_SLANDO    = 'slando';

    const TYPE_SELL     = 'sell';
    const TYPE_BUY      = 'buy';
    const TYPE_RENT     = 'rent';

    const RENT_TYPE_HOUR    = 'hour';
    const RENT_TYPE_DAY     = 'day';
    const RENT_TYPE_LONG    = 'long';

    const HOUSE_TYPE_NEW = 'new';
    const HOUSE_TYPE_OLD = 'old';

    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_UAH = 'UAH';

    const WALL_TYPE_BRICK       = 'brick';
    const WALL_TYPE_PANEL       = 'panel';
    const WALL_TYPE_BLOCK       = 'block';
    const WALL_TYPE_MONOLITH    = 'monolith';
    const WALL_TYPE_WOOD        = 'wood';

    const BRICK_TYPE_WHITE  = 'white';
    const BRICK_TYPE_RED    = 'red';

    const HEATING_TYPE_CENTRAL      = 'central';
    const HEATING_TYPE_INDEPENDENT  = 'independent';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn
     */
    protected $user;

    /** @ORM\Column(type="string") */
    protected $sourceType = self::SOURCE_TYPE_INTERNAL;

    /** @ORM\Column(type="string", nullable=true) */
    protected $sourceId;

    /** @ORM\Column(type="text", nullable=true) */
    protected $sourceURL;

    /** @ORM\Column(type="string") */
    protected $type;

    /** @ORM\Column(type="string") */
    protected $rentType;

    /** @ORM\Column(type="string", nullable=true) */
    protected $houseType;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="text") */
    protected $description;

    /** @ORM\Column(type="integer") */
    protected $price;

    /** @ORM\Column(type="string") */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="AdvertisementPhoto", mappedBy="advertisement")
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="advertisement")
     */
    protected $rooms;

    /** @ORM\Column(type="float") */
    protected $fullSpace;

    /** @ORM\Column(type="float") */
    protected $livingSpace;

    /** @ORM\Column(type="smallint") */
    protected $level;

    /** @ORM\Column(type="smallint") */
    protected $maxLevels; //only if not house

    /** @ORM\Column(type="string", nullable=true) */
    protected $wallType;

    /** @ORM\Column(type="string", nullable=true) */
    protected $brickType;

    /**
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn
     */
    protected $address;


    protected $haveGarage;
    protected $haveVault;

    /** @ORM\Column(type="string", nullable=true) */
    protected $heatingType;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var datetime $contentChanged
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"title", "body"})
     */
    protected $contentChanged;

}