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

abstract class BaseService
{
    const SAVE_ENTITY_SUCCESS = 1;
    const SAVE_ENTITY_FAIL = 2;
    const SAVE_ENTITY_BREAK = 3;

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
        $guzzle->setDefaultOption('headers', array(
            'Accept'            => '*/*',
            'Accept-Encoding'   => 'gzip,deflate,sdch',
            'Accept-Language'   => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,de;q=0.2,es;q=0.2,fr;q=0.2,pt;q=0.2,uk;q=0.2,pl;q=0.2',
            'Cache-Control'     => 'max-age=0',
            'Connection'        => 'keep-alive',
        ));
        $guzzle->setUserAgent('Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.38');
        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $guzzle->addSubscriber($cookiePlugin);
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

    public function postPageContent($url, $headers = null, $postBody = null, $options = array())
    {
        $guzzle = $this->client->getClient();
        $request = $guzzle->post($url, $headers, $postBody, $options);
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
