<?php
namespace HouseFinder\CoreBundle\Service;

use HouseFinder\CoreBundle\Entity\Address;

class AddressService
{
    /**
     * @param Address $address
     * @return array
     */
    public function getAddressREST(Address $address)
    {
        $addressData = array(
            'id'                => $address->getId(),
            'locality'          => $address->getLocality(),
            'region'            => $address->getRegion(),
            'street'            => $address->getStreet(),
            'streetNumber'      => $address->getStreetNumber(),
        );
        return $addressData;
    }
}