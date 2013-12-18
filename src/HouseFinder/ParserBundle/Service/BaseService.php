<?php

namespace HouseFinder\ParserBundle\Service;

use HouseFinder\ParserBundle\Crawler\BaseCrawler;
use HouseFinder\ParserBundle\Parser\BaseParser;
use Symfony\Component\DependencyInjection\Container;


abstract class BaseService
{
    protected $container;
    protected $crawler;
    protected $parser;
    protected $options = array();

    /**
     * @param Container $container
     * @param BaseCrawler $crawler
     * @param BaseParser $parser
     */
    public function __construct(Container $container, BaseCrawler $crawler, BaseParser $parser)
    {
        $this->container = $container;
        $this->crawler = $crawler;
        $this->parser = $parser;
    }

    public function fillLastAdvertisements($url)
    {
        $options['url'] = $url;
        $this->crawler->fetchUrlData($options);
        $entities = $this->parser->getEntities($this->crawler);
        echo "<pre>";
        var_dump($entities);
        exit;
    }

}
