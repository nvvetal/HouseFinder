<?php

namespace HouseFinder\ParserBundle\Crawler;

use Buzz\Message\MessageInterface;
use Symfony\Component\DependencyInjection\Container;

abstract class BaseCrawler
{
    protected $container;
    protected $urlData;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $options
     * @throws \Exception
     * @return MessageInterface
     */

    public function fetchUrlData(array $options = array())
    {
        if(!isset($options['url'])) throw new \Exception('URL is not provided!');
        $this->urlData = NULL;
        $buzz = $this->container->get('buzz');
        $response = $buzz->get($options['url']);
        $this->urlData = $response;
        return $response;
    }

    /**
     * @return MessageInterface
     */
    public function getUrlData()
    {
        return $this->urlData;
    }
}


