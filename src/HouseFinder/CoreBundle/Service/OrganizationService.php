<?php

namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\Organization;
use HouseFinder\CoreBundle\Entity\OrganizationRepository;

class OrganizationService
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param Address $address
     * @param $name
     * @return Organization
     */
    public function getOrganization(Address $address, $name)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('Doctrine')->getManager();
        /** @var OrganizationRepository $repo */
        $repo = $em->getRepository('HouseFinderCoreBundle:Organization');
        $organization = $repo->findOneByAddressAndName($address, $name);
        return $organization;
    }

    /**
     * @param Address $address
     * @param $name
     * @param $description
     * @return Organization
     */
    public function createOrganization(Address $address, $name, $description)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('Doctrine')->getManager();
        $organization = new Organization();
        $organization->setAddress($address);
        $organization->setName($name);
        $organization->setDescription($description);
        $organization->setCreated(new \DateTime());
        $em->persist($organization);
        $em->flush($organization);
        return $organization;

    }
}