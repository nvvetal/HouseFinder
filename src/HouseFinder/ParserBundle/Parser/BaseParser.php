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
        if(mb_stripos($text, 'кирп.', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, 'кирпич', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, 'панель', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_PANEL;
        return NULL;
    }

    public function parseFullLiveKitchenSpace($text)
    {
        $text = str_replace(",", ".", $text);
        if(preg_match("~([\d.]+)[\s/]+([\d.]+)[\s/]+([\d.]+)~", $text, $m)){
            return array(
                'fullSpace'     => trim($m[1], '.'),
                'livingSpace'   => trim($m[2], '.'),
                'kitchenSpace'  => trim($m[3], '.'),
            );
        }
        return NULL;
    }

    public function parseTextBalcony($text)
    {
        if(mb_stripos($text, 'балкон', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextPhone($text)
    {
        if(mb_stripos($text, 'телефон', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextIndependentHeating($text)
    {
        if(mb_stripos($text, 'автономка', 0, 'UTF-8') !== false) return Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автономное отопление', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        return NULL;
    }

    public function parseTextVault($text)
    {
        if(mb_stripos($text, 'подвал', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextGarage($text)
    {
        if(mb_stripos($text, 'гараж', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextWindowPlastic($text)
    {
        if(mb_stripos($text, 'мпо', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'пластиковые окна', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'стеклопакет', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'евроокна', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextConditioner($text)
    {
        if(mb_stripos($text, 'кондиционер', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextGasBoiler($text)
    {
        if(mb_stripos($text, 'газовая колонка', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextElectricalBoiler($text)
    {
        if(mb_stripos($text, 'бойлер', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextLaminate($text)
    {
        if(mb_stripos($text, 'ламинат', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextParquet($text)
    {
        if(mb_stripos($text, 'паркет', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextWithDocuments($text)
    {
        if(mb_stripos($text, 'документы готовы', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextFurniture($text)
    {
        if(mb_stripos($text, 'без мебели', 0, 'UTF-8') !== false) return false;
        if(mb_stripos($text, 'мебель', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextRefrigerator($text)
    {
        if(mb_stripos($text, 'холодильник', 0, 'UTF-8') !== false) return true;
        return false;
    }
}
