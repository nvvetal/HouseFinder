<?php

namespace HouseFinder\ParserBundle\Parser;

use Buzz\Message\MessageInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Service\BaseService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;


abstract class BaseParser
{
    protected $container;
    protected $service;

    public function __construct(Container $container, BaseService $service)
    {
        $this->container = $container;
        $this->service = $service;
    }

    /**
     * @param Crawler $crawler
     * @return mixed
     */
    abstract protected function parseListDomCrawler(Crawler $crawler);

    /**
     * @param Crawler $crawler
     * @return array
     */
    abstract protected function parsePageDomCrawler(Crawler $crawler);

    /**
     * @param string|string $raw
     * @return Advertisement;
     */
    abstract protected function getEntityByRAW($raw);

    /**
     * @param Crawler $crawler
     * @return array|null
     */
    public function getEntities(Crawler $crawler)
    {
        $rows = $this->parseListDomCrawler($crawler);
        if(count($rows) == 0) return NULL;
        $entities = array();
        foreach ($rows as $row)
        {
            $entities[] = $this->getEntityByRAW($row);
        }
        return $entities;
    }
}
