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
use HouseFinder\CoreBundle\AddressEvents;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\AddressRepository;
use HouseFinder\CoreBundle\Event\FilterAddressCreateEvent;
use Ivory\GoogleMap\Services\Geocoding\Geocoder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AddressService
{
    /** @var Geocoder */
    protected $geocoder;
    /** @var  EntityManager */
    protected $em;
    protected $event_dispatcher;

    public function __construct(Geocoder $geocoder, EntityManager $em, EventDispatcher $event_dispatcher)
    {
        $this->geocoder = $geocoder;
        $this->em = $em;
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * @param $addressOrig
     * @internal param string $address String representation of address
     * @return Address
     */
    public function getAddress($addressOrig)
    {
        try {
            /** @var Geocoded $response */
            $response = $this->geocoder->geocode($addressOrig);
            $address = new Address();
            $address->setStreetNumber($response->getStreetNumber());
            $address->setStreet($response->getStreetName());
            $address->setLocality($response->getCity());
            $address->setRegion($response->getRegion());
            $address->setOriginal($addressOrig);
            $coordinates = $response->getCoordinates();

            $address->setLatitude($coordinates[0]);
            $address->setLongitude($coordinates[1]);

            /** @var AddressRepository $addressRepository */
            $addressRepository = $this->em->getRepository('HouseFinder\CoreBundle\Entity\Address');
            $a2 = $addressRepository->findOneByAddress($address);
            if (!empty($a2)) {
                return $a2;
            }
            $this->em->persist($address);
            $this->em->flush($address);
            $event = new FilterAddressCreateEvent($address);
            $this->event_dispatcher->dispatch(AddressEvents::ADDRESS_CREATE, $event);
        }catch(\Exception $e){
            echo $e->getMessage().'['.$addressOrig.']'."\n";
            return NULL;
        }
        return $address;
    }
}
