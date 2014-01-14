<?php
/**
 * Created by PhpStorm.
 * User: nvvetal
 * Date: 13.01.14
 * Time: 22:44
 */

namespace HouseFinder\StorageBundle\Image;

use Doctrine\ORM\EntityManager;

class AdvertisementPhoto extends BaseImage {

    public function __construct($container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
        $this->init();
    }

    public function setContext()
    {
        $this->context = 'advertisement_photo';
    }
}