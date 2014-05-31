<?php
namespace HouseFinder\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use HouseFinder\CoreBundle\Entity\Address;

class FilterAddressCreateEvent extends Event
{
    protected $address;


    /**
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

}

