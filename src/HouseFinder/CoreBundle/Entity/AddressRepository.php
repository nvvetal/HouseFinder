<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 11.01.14
 * Time: 13:47
 */

namespace HouseFinder\CoreBundle\Entity;


use Doctrine\ORM\EntityRepository;

class AddressRepository extends EntityRepository
{
    public function findOneByAddress(Address $address)
    {
        return $this->findOneBy(
            array(
                'region' => $address->getRegion(),
                'locality' => $address->getLocality(),
                'street' => $address->getStreet(),
                'streetNumber' => $address->getStreetNumber()
            )
        );
    }
}
