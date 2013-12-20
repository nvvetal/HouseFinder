<?php

namespace HouseFinder\CoreBundle\Entity;

class Advertisement
{
    //link to internal user
    protected $user;

    //slando, internal...
    protected $sourceType;
    protected $sourceId;
    protected $sourceURL;
    //private, realtor, construction_company, bank
    protected $sellerType; //seems move to user
    protected $sellerName; //seems move to user


    //sell, buy, rent
    protected $type;
    //hourly, daily, long-term
    protected $rentType;
    //new, second
    protected $sellType;


    protected $name;
    protected $description;
    protected $price;
    protected $currency;
    protected $photos;
    protected $phones; //seems move to user
    protected $roomsCount;
    protected $fullSpace;
    protected $livingSpace;
    protected $kitchenSpace;
    protected $level;
    protected $maxLevels; //only if not house
    //brick, panel, block, monolith, wood
    protected $wallType;
    //red, white
    protected $brickType;
    //link to streets
    protected $street;
    //link to house if we are know house number (strict for internal)
    protected $house;
    protected $haveGarage;
    protected $haveVault;
    //independent, central
    protected $heatingType;
    protected $dateTimeCreated;

}