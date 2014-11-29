<?php
namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\AddressRepository;

class AddressService
{
    /** @var EntityManager $em */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @return Address
     */
    public function getAddressByCityName($name)
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        return $repo->findCityByName($name);
    }

    /**
     * @param float $lat
     * @param float $long
     * @return Address
     */
    public function getAddressCityNearCoords($lat, $long)
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        return $repo->findCityNearByLatLong($lat, $long);
    }

    /**
     * @return array
     */
    public function getCities()
    {
        /** @var AddressRepository $repo */
        $repo = $this->em->getRepository('HouseFinderCoreBundle:Address');
        return $repo->findCitiesAll();
    }
}