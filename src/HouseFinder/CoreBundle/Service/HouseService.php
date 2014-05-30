<?php

namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\House;

class HouseService
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param Address $address
     * @return House
     */
    public function createFromAddress(Address $address)
    {
        $house = new House();
        $house->setAddress($address);
        $em = $this->container->get('Doctrine')->getManager();
        $em->persist($house);
        $em->flush($house);
        return $house;
    }

    /**
     * @param Address $address
     * @return House
     */
    public function getHouseByAddress(Address $address)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('Doctrine')->getManager();
        /** @var House $house */
        $house =  $em->getRepository('HouseFinderCoreBundle:House')->findOneBy(array(
            'Address' => $address,
        ));
        return $house;
    }

}