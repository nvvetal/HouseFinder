<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 10.01.14
 * Time: 14:20
 */

namespace HouseFinder\ParserBundle\Service;

use Doctrine\ORM\EntityManager;
use Geocoder\Exception\NoResultException;
use Geocoder\Result\Geocoded;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\AddressRepository;
use Ivory\GoogleMap\Services\Geocoding\Geocoder;

class AddressService
{
    /** @var Geocoder */
    protected $geocoder;
    /** @var  EntityManager */
    protected $em;

    public function __construct(Geocoder $geocoder, EntityManager $em)
    {
        $this->geocoder = $geocoder;
        $this->em = $em;
    }

    /**
     * @param $addressOrig
     * @internal param string $address String representation of address
     * @return Address
     */
    public function getAddress($addressOrig)
    {
        /** @var Geocoded $response */
        $response = $this->geocoder->geocode($addressOrig);
        $address = new Address();
        $address->setStreetNumber($response->getStreetNumber());
        $address->setStreet($response->getStreetName());
        $address->setLocality($response->getCity());
        $address->setRegion($response->getRegion());
        $address->setOriginal($addressOrig);
        /** @var AddressRepository $addressRepository */
        $addressRepository = $this->em->getRepository('HouseFinder\CoreBundle\Entity\Address');
        $a2 = $addressRepository->findOneByAddress($address);
        if (!empty($a2)) {
            return $a2;
        }
        $this->em->persist($address);
        $this->em->flush($address);

        return $address;
    }
}
