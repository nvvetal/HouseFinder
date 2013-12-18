<?php

namespace HouseFinder\ParserBundle\Parser;

use Buzz\Message\MessageInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Crawler\BaseCrawler;
use Symfony\Component\DependencyInjection\Container;

abstract class BaseParser
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    abstract protected function parseContent();

    /**
     * @param string|string $raw
     * @return Advertisement;
     */
    abstract protected function getEntityByRAW($raw);

    /**
     * @param BaseCrawler $crawler
     * @return array|null
     * @throws \Exception
     */
    public function getEntities(BaseCrawler $crawler)
    {
        $data = $crawler->getUrlData();
        $content = $data->getContent();
        if(empty($content)) throw new \Exception('Empty content!');
        $rows = $this->parseContent();
        if(count($rows) == 0) return NULL;
        $entities = array();
        foreach ($rows as $row)
        {
            $entities[] = $this->getEntityByRAW($row);
        }
        return $entities;
    }
}
