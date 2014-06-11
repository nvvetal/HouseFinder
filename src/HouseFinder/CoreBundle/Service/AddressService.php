<?php
namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\AddressRepository;

class AddressService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

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

    /**
     * @param string $name
     * @return Address
     */
    public function getAddressByCityNameREST($name)
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        $address = $repo->findCityByName($name);
        $data = NULL;
        if(is_null($address)) return $data;
        $data = array(
            'id'                => $address->getId(),
            'locality'          => $address->getLocality(),
            'region'            => $address->getRegion(),
            'latitude'          => $address->getLatitude(),
            'longitude'         => $address->getLongitude(),
        );
        return $data;
    }

    /**
     * @param $lat
     * @param $long
     * @return Address
     */
    public function getAddressCityNearCoordsREST($lat, $long)
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        $address = $repo->findCityNearByLatLong($lat, $long);
        $data = NULL;
        if(is_null($address)) return $data;
        //var_dump($address);
        $data = array(
            'id'                => $address[0]->getId(),
            'locality'          => $address[0]->getLocality(),
            'region'            => $address[0]->getRegion(),
            'latitude'          => $address[0]->getLatitude(),
            'longitude'         => $address[0]->getLongitude(),
        );
        return $data;
    }
}