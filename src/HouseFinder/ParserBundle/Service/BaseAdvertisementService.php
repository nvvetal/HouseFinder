<?php

namespace HouseFinder\ParserBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementExternal;
use HouseFinder\ParserBundle\Parser\BaseParser;
use HouseFinder\ParserBundle\Parser\SlandoParser;
use Symfony\Component\DependencyInjection\Container;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

abstract class BaseAdvertisementService extends BaseService
{
    /**
     * @param AdvertisementExternal $entity
     * @return mixed
     */
    abstract function saveAdvertisementEntity(AdvertisementExternal &$entity);

    abstract function fillLastAdvertisements($url, $type);
}