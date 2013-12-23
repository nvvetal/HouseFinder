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
    protected $advertisementType;

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
     * @param $type
     * @return array|null
     */
    public function getEntities(Crawler $crawler, $type = Advertisement::TYPE_RENT )
    {
        $this->advertisementType = $type;
        $rows = $this->parseListDomCrawler($crawler);
        if(count($rows) == 0) return NULL;
        $entities = array();
        foreach ($rows as $row)
        {
            $entity = $this->getEntityByRAW($row);
            $this->postParseText($entity);
            $entities[] = $entity;
        }
        /*
        header('Content-Type: text/html; charset=utf-8');
        echo "<pre>";
        var_dump($entities);
        exit;
        */
        $this->advertisementType = NULL;
        return $entities;
    }

    abstract public function postParseText(Advertisement &$entity);

    public function parseTextWallType($text)
    {
        if(mb_strpos($text, 'кирпич', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_strpos($text, 'панель', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_PANEL;
        return '';
    }
}
