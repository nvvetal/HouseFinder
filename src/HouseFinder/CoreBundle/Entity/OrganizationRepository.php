<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 11.01.14
 * Time: 13:47
 */

namespace HouseFinder\CoreBundle\Entity;
use Doctrine\ORM\EntityRepository;

class OrganizationRepository extends EntityRepository
{
    public function findOneByAddressAndName(Address $address, $name)
    {
        return $this->findOneBy(
            array(
                'address'   => $address,
                'name'      => $name,
            )
        );
    }
}
