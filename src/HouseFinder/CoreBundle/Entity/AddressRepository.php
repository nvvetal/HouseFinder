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
    /**
     * @param Address $address
     * @return Address
     */
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

    /**
     * @param string $name
     * @return Address
     */
    public function findCityByName($name)
    {
        return $this->findOneBy(
            array(
                'locality'      => $name,
                'street'        => NULL,
                'streetNumber'  => NULL
            )
        );
    }

    /**
     * @param $lat
     * @param $long
     * @return Address
     */
    public function findCityNearByLatLong($lat, $long)
    {
        $q = $this->createQueryBuilder('c');
        $q->addSelect('c, (SQRT(pow((:lat - c.latitude), 2) + pow((:long - c.longitude), 2))) as distance');
        $q->andWhere('c.street IS NULL');
        $q->andWhere('c.streetNumber IS NULL');
        $q->orderBy('distance', 'ASC');
        $q->setParameter(':lat', $lat);
        $q->setParameter(':long', $long);
        $q->setMaxResults(1);
        return $q->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array
     */
    public function findCitiesAll()
    {
        $q = $this->createQueryBuilder('c');
        $q->andWhere('c.street IS NULL');
        $q->andWhere('c.streetNumber IS NULL');
        $q->andWhere('c.locality IS NOT NULL');
        $q->andWhere('c.locality != :empty');
        $q->setParameter(':empty', '');
        return $q->getQuery()->getResult();
    }
}
