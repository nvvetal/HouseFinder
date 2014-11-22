<?php

namespace HouseFinder\ParserBundle\Parser;

use Buzz\Message\MessageInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Service\LoggerService;
use HouseFinder\ParserBundle\Entity\BaseParserEntity;
use HouseFinder\ParserBundle\Service\BaseService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;


abstract class BaseParser
{
    protected $container;
    protected $service;
    /** @var LoggerService $logger */
    protected $logger;
    protected $advertisementType;

    public function __construct(Container $container, BaseService $service)
    {
        $this->container = $container;
        $this->service = $service;
        $this->logger = $container->get('housefinder.service.logger');
    }

    /**
     * @param Crawler $crawler
     * @return mixed
     */
    abstract protected function fetchLinks(Crawler $crawler);

    /**
     * @param Crawler $crawler
     * @return array
     */
    abstract protected function parsePageDomCrawler(Crawler $crawler);

    /**
     * @param array $link
     * @return mixed
     */
    abstract protected function fetchPageByLink(array $link);



    /**
     * @param BaseParserEntity $raw
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
        $links = $this->fetchLinks($crawler);
        if(count($links) == 0) return NULL;
        $entities = array();
        foreach ($links as $link)
        {
            if(!is_array($link)) continue;
            $res = $this->fetchPageByLink($link);
            if($res['res'] != 'ok' && $res['res'] != 'exist') continue;
            $entity = $this->getEntityByRAW($res['entity']);
            if(is_null($entity)) continue;
            $this->postParseText($entity);
            $entities[] = $entity;

        }
        $this->advertisementType = NULL;
        return $entities;
    }

    abstract public function postParseText(Advertisement &$entity);

    public function parseTextWallType($text)
    {
        if(mb_stripos($text, 'кирп.', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, 'кирпич', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, ' кир ', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, 'панель', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_PANEL;
        if(mb_stripos($text, 'цегляний', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        if(mb_stripos($text, 'кипрпич', 0, 'UTF-8') !== false) return Advertisement::WALL_TYPE_BRICK;
        return '';
    }

    public function parseFullLiveKitchenSpace($text)
    {
        $text = str_replace(",", ".", $text);
        $text = str_replace("\\", "/", $text);
        if(preg_match("/([\d.]+)\s*\/\s*([\d.]+)\s*\/\s*([\d.]+)/", $text, $m)){
            return array(
                'fullSpace'     => trim($m[1], '.'),
                'livingSpace'   => trim($m[2], '.'),
                'kitchenSpace'  => trim($m[3], '.'),
            );
        }
        return NULL;
    }

    public function parseLevels($text)
    {
        $text = str_replace(",", ".", $text);
        $text = preg_replace("~(\d)\s*[/\\\]\s*(\d)~","$1/$2", $text);
        if(preg_match("/(?<!\d|\d\/)(\d+)\/(\d+)(?!\d|\/\d)/", $text, $m)){
            return array(
                'level'     => $m[1],
                'maxLevels' => $m[2],
            );
        }
        return NULL;
    }

    public function parseTextBalcony($text)
    {
        if(mb_stripos($text, 'без балкона', 0, 'UTF-8') !== false) return false;
        if(mb_stripos($text, 'балкон', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'балк.', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'б\з', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'б/з', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextLoggia($text)
    {
        if(mb_stripos($text, 'лоджия', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'лоджии', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'лоджией', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextPhone($text)
    {
        if(mb_stripos($text, 'подробности по телефону', 0, 'UTF-8') !== false) return false;
        if(mb_stripos($text, 'телефон', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextIndependentHeating($text)
    {
        if(mb_stripos($text, 'автономка', 0, 'UTF-8') !== false) return Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автономное отопление', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'электроконвектор', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автон. отоплен.', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'с автономным отоплением', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автономне опалення', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автономным отоплением', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_strpos($text, 'АО', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'авт. отопл.', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        if(mb_stripos($text, 'автоном.отопление', 0, 'UTF-8') !== false) return  Advertisement::HEATING_TYPE_INDEPENDENT;
        return '';
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
        if(mb_stripos($text, 'м/п окна', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'метало-пластик', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'мп окна', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'металопластикові вікна', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'металлопластиковое окно', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'м/п/окна', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextConditioner($text)
    {
        if(mb_stripos($text, 'кондиционер', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextGasBoiler($text)
    {
        if(mb_stripos($text, 'колонка', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'калонка', 0, 'UTF-8') !== false) return true;
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
        return $this->parseTextFurnitureKitchenIntegrated($text);
        //return false;
    }

    public function parseTextRefrigerator($text)
    {
        if(mb_stripos($text, 'холодильник', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextCanTrade($text)
    {
        if(mb_stripos($text, 'торг', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextWCIndependent($text)
    {
        if(mb_stripos($text, 'Санузел раздельный', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'раздельный санузел', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'с/у разд', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'с/узел раздельный', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextWCShared($text)
    {
        if(mb_stripos($text, 'саузел совместно', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'с\у совместный', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'с/у/см', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextCounters($text)
    {
        if(mb_stripos($text, 'счетчик', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'счётчик', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'сч. на ', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextArmoredDoor($text)
    {
        if(mb_stripos($text, 'бронированная дверь', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'бронедверь', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'дверь бронированная', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'бронировая дверь', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextHangar($text)
    {
        if(mb_stripos($text, 'сарай', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextInternet($text)
    {
        if(mb_stripos($text, 'интернет', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextPantry($text)
    {
        if(mb_stripos($text, 'кладовка', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'кладовая', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'кладовки', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextFurnitureKitchenIntegrated($text)
    {
        if(mb_stripos($text, 'встроенная кухня', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'кухонная мебель', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'встр. кухня', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextNotCorner($text)
    {
        if(mb_stripos($text, 'не угловая', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'не гуловая', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'не гловая', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'не углова', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextDoorsWindows($text)
    {
        if(mb_stripos($text, 'столярка', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextIntercom($text)
    {
        if(mb_stripos($text, 'домофон', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextCableTV($text)
    {
        if(mb_stripos($text, 'кабельное', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextTV($text)
    {
        if(mb_stripos($text, 'телевизор', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'плазма', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextTech($text)
    {
        if(mb_stripos($text, 'бытовая техника', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'быт.техника', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'обставлена техникой', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextRoomsSeparated($text)
    {
        if(mb_stripos($text, 'комнаты раздельные', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextRoomsAdjacent($text)
    {
        if(mb_stripos($text, 'комнаты смежные', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextShower($text)
    {
        if(mb_stripos($text, 'душевая кабина', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextWashingMachine($text)
    {
        if(mb_stripos($text, 'стиральная машина', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'стир.машинка', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'стиралка', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextMicrowave($text)
    {
        if(mb_stripos($text, 'микроволновая печь', 0, 'UTF-8') !== false) return true;
        if(mb_stripos($text, 'СВЧ печь', 0, 'UTF-8') !== false) return true;
        return false;
    }

    public function parseTextKitchenHood($text)
    {
        if(mb_stripos($text, 'вытяжка', 0, 'UTF-8') !== false) return true;
        return false;
    }
}
