<?php

namespace HouseFinder\ParserBundle\Service;

use HouseFinder\ParserBundle\Parser\BaseParser;
use Symfony\Component\DependencyInjection\Container;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

abstract class BaseService
{
    protected $container;
    protected $client;
    protected $options = array();

    /**
     * @param Container $container
     * @internal param \Goutte\Client $client
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->client = new Client();
        $guzzle = $this->client->getClient();
        $guzzle->setUserAgent('Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.37');
        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $guzzle->addSubscriber($cookiePlugin);
    }

    public function fillLastAdvertisements($url)
    {
        $domCrawler = $this->getPageCrawler($url);
        $parser = $this->container->get('housefinder.parser.parser.slando');
        $entities = $parser->getEntities($domCrawler);
        echo "<pre>";
        var_dump($entities);
        exit;
    }

    /**
     * @param $url
     * @return Crawler
     */
    public function getPageCrawler($url)
    {
        return new Crawler($this->client->getClient()->get($url)->send()->getBody(true));
    }

    public function getClientHistory()
    {
        return $this->client->getHistory();
    }

    public function getPageContent($url)
    {
        $guzzle = $this->client->getClient();
        $request = $guzzle->get($url);
        $response = $request->send();
        return $response->getBody(true);
    }

    public function getFile($url)
    {
        $guzzle = $this->client->getClient();
        $request = $guzzle->get($url);
        $response = $request->send();
        $tmpDir = $this->container->getParameter("kernel.cache_dir");
        $fileName = tempnam($tmpDir, 'ocr_');
        file_put_contents($fileName, $response->getBody(true));
        return $fileName;
    }

}
