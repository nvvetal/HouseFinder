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

    private function formatAddressREST(Address $address)
    {
        $addressData = array(
            'id'                => $address->getId(),
            'locality'          => $address->getLocality(),
            'region'            => $address->getRegion(),
            'street'            => $address->getStreet(),
            'streetNumber'      => $address->getStreetNumber(),
            'latitude'          => $address->getLatitude(),
            'longitude'         => $address->getLongitude(),
        );
        return $addressData;
    }

    public function formatAddressLineREST(Address $address)
    {
        $line = array();
        $line[] = $address->getLocality();
        $street = $address->getStreet();
        $number = $address->getStreetNumber();
        if(!empty($street)){
            $line[] = $street;
            if(!empty($number)) $line[] = $number;
        }
        return implode(', ', $line);
    }

    /**
     * @param Address $address
     * @return array
     */
    public function getAddressREST(Address $address)
    {
        return $this->formatAddressREST($address);
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
        $data = $this->formatAddressREST($address);
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
        $data = $this->formatAddressREST($address[0]);
        return $data;
    }

    /**
     * @return array
     */
    public function getAddressCitiesREST()
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        $addresses = $repo->findCitiesAll();
        $data = array();
        if(count($addresses) == 0) return $data;
        foreach($addresses as $address) {
            $data[] = $this->formatAddressREST($address);
        }
        return $data;
    }
}