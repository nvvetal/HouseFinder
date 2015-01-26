<?php
namespace HouseFinder\ParserBundle\Parser;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\CoreBundle\Entity\AdvertisementSlando;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Service\KvartiraZhitomirUa\AdvertisementKvartiraZhitomirUaService;
use HouseFinder\CoreBundle\Service\Slando\AdvertisementSlandoService;
use HouseFinder\CoreBundle\Service\Slando\UserSlandoService;
use HouseFinder\ParserBundle\Entity\BaseParserEntity;
use HouseFinder\ParserBundle\Entity\KvartiraZhitomirUa\KvartiraZhitomirUaParserEntity;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;
use HouseFinder\ParserBundle\Service\KvartiraZhitomirUaService;
use HouseFinder\ParserBundle\Service\SlandoService;
use phpOCR\cOCR;
use Symfony\Component\DomCrawler\Crawler;


class KvartiraZhitomirUaParser extends BaseParser
{
    protected $url = 'http://kvartira.zhitomir.ua';
    protected $logName = 'parser_kvartira_zhitomir_ua';

    /**
     * @param Crawler $crawler
     * @return mixed|void
     */
    protected function fetchLinks(Crawler $crawler)
    {
        $self = &$this;
        $links = $crawler->filter('table[rules=rows] tr')->each(function (Crawler $node, $i) use ($self) {
            try {
                $container = $node->filter('td')->eq(2);
                $title = $container->filter('b a u')->text();
                $url = $container->filter('b a')->attr('href');
                $phonesData = $container->filter('table tr[valign=bottom] td[align=left] small')->eq(0)->text();
                $phones = array();
                foreach(explode(',', $phonesData) as $phone){
                    $phone = $this->normalizePhone($phone);
                    if(!is_null($phone)) $phones[] = $phone;
                }
            }catch(\Exception $e){
                return false;
            }
            $sourceHash = NULL;
            if(preg_match("/(\d+)\.html/i", $url, $m)){
                $sourceHash = $m[1];
            }
            $data = array(
                'title'         => $title,
                'url'           => $url,
                'sourceHash'    => $sourceHash,
                'phones'        => $phones,
            );
            return $data;
        });
        return $links;
    }

    /**
     * @param array $link
     * @return SlandoParserEntity|mixed
     */
    protected function fetchPageByLink(array $link){
        /** @var KvartiraZhitomirUaService $service */
        $service = $this->container->get('housefinder.parser.service.kvartira_zhitomir_ua');
        $crawlerPage = $service->getPageCrawler($this->url.$link['url']);
        try {
            $parserEntity = $this->parsePageDomCrawler($crawlerPage);
            $parserEntity->setSourceHash($link['sourceHash']);
            $parserEntity->setSourceId($link['sourceHash']);
            $parserEntity->setSourceURL($this->url.$link['url']);
            $parserEntity->setName($link['title']);
            $parserEntity->setPhones($link['phones']);
            if(is_null($parserEntity->getDescription())) $parserEntity->setDescription($link['title']);
            return array(
                'res' => 'ok',
                'entity' => $parserEntity,
            );
        }catch(\Exception $e){
            echo $err = "Error on page ".$this->url."{$link['url']} :".$e->getMessage();
            return array(
                'res'     => 'error',
                'message' => $err,
            );
        }
    }

    /**
     * @param Crawler $crawler
     * @return KvartiraZhitomirUaParserEntity
     */
    protected function parsePageDomCrawler(Crawler $crawler)
    {
        /** @var UserSlandoService $userService */
        $userService = $this->container->get('housefinder.service.slando.user');
        $parserEntity = new KvartiraZhitomirUaParserEntity();
        $parserEntity->setType($this->advertisementType);
        $parserEntity->setParams($this->getParams($crawler));
        $parserEntity->setDescription($crawler->filter('h2 font[color="BLACK"]')->text());

        $priceData = $this->getPriceData($crawler->filter("table tr td[align='right'] h2")->eq(0)->text());
        $parserEntity->setPrice($priceData['price']);
        $parserEntity->setCurrency($priceData['currency']);

        $owner = $this->getOwnerData($crawler);
        $parserEntity->setOwnerName($owner['name']);
        $createdUpdated = $this->getCreatedUpdatedDateTime($crawler);
        $parserEntity->setCreatedDateTime($createdUpdated['created']);
        $parserEntity->setUpdatedDateTime($createdUpdated['updated']);
        $this->fillAddress($parserEntity);
        $this->fillRooms($parserEntity);
        $this->fillFullSpace($parserEntity);
        $this->fillLivingSpace($parserEntity);
        $this->fillLevel($parserEntity);
        $this->fillMaxLevels($parserEntity);
        $this->fillWallType($parserEntity);
        $parserEntity->setHouseType(AdvertisementSlando::HOUSE_TYPE_OLD);

        $parserEntity->setPhotos($crawler->filter("table[id=imagerows] tr td a img")->each(function(Crawler $node){
            return str_replace('thumb_', '', $node->attr('src'));
        }));
        return $parserEntity;
    }

    /**
     * @param BaseParserEntity $raw
     * @return Advertisement;
     */
    protected function getEntityByRAW($raw)
    {
        /** @var AdvertisementKvartiraZhitomirUaService $service */
        $service = $this->container->get('housefinder.service.kvartira_zhitomir_ua.advertisement');
        return $service->fillByRaw($raw);
    }

    /**
     * @param Advertisement $entity
     */
    public function postParseText(Advertisement &$entity)
    {
        $params = $entity->getParams();
        if(isset($params['Балкон'])) {
            $entity->setSpecial('balcony', true);
            foreach($params['Балкон'] as $key=>$txt){
                if($this->parseTextLoggia($txt)) $entity->setSpecial('loggia', true);
            }
        }
        if(isset($params['Окна'])){
            foreach($params['Окна'] as $key=>$txt){
                if($this->parseTextWindowPlastic($txt)) $entity->setSpecial('windowPlastic', true);
            }
        }
        if(isset($params['Пол'])){
            foreach($params['Пол'] as $key=>$txt){
                if($this->parseTextLaminate($txt)) $entity->setSpecial('laminate', true);
                if($this->parseTextParquet($txt)) $entity->setSpecial('parquet', true);
            }
        }
        if(isset($params['Санузел'])){
            foreach($params['Санузел'] as $key=>$txt){
                if($this->parseTextWCIndependent('Санузел '.$txt)) $entity->setSpecial('wcIndependent', true);
                if($this->parseTextWCShared('Санузел '.$txt)) $entity->setSpecial('wcShared', true);
                if($this->parseTextGasBoiler($txt)) $entity->setSpecial('boilerGas', true);
                if($this->parseTextElectricalBoiler($txt)) $entity->setSpecial('boilerElectro', true);
            }
        }
        if(isset($params['Двери'])){
            foreach($params['Двери'] as $key=>$txt){
                if($this->parseTextArmoredDoor('Дверь '.$txt)) $entity->setSpecial('doorArmored', true);
            }
        }
        if(isset($params['Дополнительно'])){
            foreach($params['Дополнительно'] as $key=>$txt){
                if($this->parseTextNotCorner($txt)) $entity->setSpecial('notCorner', true);
                if($this->parseTextCounters($txt)) $entity->setSpecial('counters', true);
                if($this->parseTextFurnitureKitchenIntegrated($txt)) $entity->setSpecial('furnitureKitchen', true);
                if($this->parseTextPhone($txt)) $entity->setSpecial('phone', true);
                if($this->parseTextInternet($txt)) $entity->setSpecial('internet', true);
                if($this->parseTextIntercom($txt)) $entity->setSpecial('intercom', true);
                if($this->parseTextCableTV($txt)) $entity->setSpecial('cableTV', true);
                if($this->parseTextTV($txt)) $entity->setSpecial('TV', true);
                if($this->parseTextWashingMachine($txt)) $entity->setSpecial('washingMachine', true);
                if($this->parseTextMicrowave($txt)) $entity->setSpecial('microwave', true);
            }
        }
        if(isset($params['Условия продажи'])){
            foreach($params['Условия продажи'] as $key=>$txt){
                if($this->parseTextCanTrade($txt)) $entity->setSpecial('trade', true);
            }
        }
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getOwnerData(Crawler $crawler)
    {
        $data = array(
            'name' => '',
        );
        $contacts = $crawler->filter("big");
        for($i = 0; $i < $contacts->count(); $i++){
            if(preg_match("/Контакты:(.*)/ims", $contacts->eq($i)->text(), $m))
            {
                $name = explode('- ', $m[1]);
                $data['name'] = substr($name[count($name)-1], 0, -1);
            }

        }
        return $data;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getCreatedUpdatedDateTime(Crawler $crawler)
    {
        $data = array(
            'created' => null,
            'updated' => null,
        );
        $html = $crawler->html();
        if(preg_match("/Дата создания\: <b>(\d+)\/(\d+)\/(\d+)<\/b>/imsu", $html, $m)){
            $data['created'] = \DateTime::createFromFormat('Y-m-d', $m[3].'-'.$m[2].'-'.$m[1]);
        }
        if(preg_match("/Дата последнего изменения\: <b>(\d+)\/(\d+)\/(\d+)<\/b>/imsu", $html, $m)){
            $data['updated'] = \DateTime::createFromFormat('Y-m-d', $m[3].'-'.$m[2].'-'.$m[1]);
        }
        return $data;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return string
     */
    private function fillAddress(KvartiraZhitomirUaParserEntity &$entity)
    {
        $address = 'Житомир';
        $street = $entity->getParamByName('Улица');
        if(!is_null($street)) $address .= ', '.$street;
        $entity->setAddress($address);
        return $address;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getParams(Crawler $crawler)
    {
        $params = array();
        try {
            //$html = $crawler->filter("table tr td[align='left'] big")->eq(0)->html();
            $big = $crawler->filter('big');
            $html = '';
            for($i = 0; $i < $big->count(); $i++){
                if(preg_match("/Населенный пункт/ims", $big->eq($i)->html())){
                    $html = $big->eq($i)->html();
                }
            }
            $items = explode('<br>', $html);
            if(count($items) == 1) throw new \Exception('Cannot find items');
            //var_dump($items);
            foreach ($items as $item) {
                $iitem = explode(': ', $item);
                $iitem[0] = str_replace(array('<strong>', '</strong>'), array('', ''), $iitem[0]);
                if (count($iitem) != 2) continue;
                $params[$iitem[0]] = $iitem[1];
            }
        }catch(\Exception $e){
            echo $e->getMessage();
            $this->logger->write('[error '.$e->getMessage().']','error_'.$this->logName);
        }
        $captions = $crawler->filter("table tr td[align='left'] div[class=multiple_options_caption]");
        $captionsData = $crawler->filter("table tr td[align='left'] div[class=multiple_options]");
        for($i = 0; $i < $captions->count(); $i++){
            $params[$captions->eq($i)->text()] = $captionsData->eq($i)->filter('ul li')->each(function(Crawler $node){
                return $node->text();
            });
        }
        return $params;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillRooms(KvartiraZhitomirUaParserEntity &$entity)
    {
        $roomsCount = $entity->getParamByName('Количество комнат');
        if(is_null($roomsCount)) return false;
        $kitchenSpace = $entity->getParamByName('Кухня(кв.м.)');
        for($i = 0; $i < $roomsCount; $i++){
            $room = new Room();
            $room->setType(Room::TYPE_ROOM);
            $entity->addRoom($room);
        }
        if($kitchenSpace !== false){
            $room = new Room();
            $room->setType(Room::TYPE_KITCHEN);
            $room->setSpace($kitchenSpace);
            $entity->addRoom($room);
        }
        return true;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillFullSpace(KvartiraZhitomirUaParserEntity &$entity)
    {
        $content = $entity->getParamByName('Общая(кв.м.)', $entity->getParams());
        if(is_null($content)) return false;
        $entity->setFullSpace($content);
        return true;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillLivingSpace(KvartiraZhitomirUaParserEntity &$entity)
    {
        $content = $entity->getParamByName('Жилая(кв.м.)', $entity->getParams());
        if(is_null($content)) return false;
        $entity->setLivingSpace($content);
        return true;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillLevel(KvartiraZhitomirUaParserEntity &$entity)
    {
        $content = $entity->getParamByName('Этаж', $entity->getParams());
        if(is_null($content)) return false;
        $entity->setLevel($content);
        return true;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillMaxLevels(KvartiraZhitomirUaParserEntity &$entity)
    {
        $content = $entity->getParamByName('Этажей', $entity->getParams());
        if(is_null($content)) return false;
        $entity->setMaxLevels($content);
        return true;
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $entity
     * @return bool
     */
    private function fillWallType(KvartiraZhitomirUaParserEntity &$entity)
    {
        $content = $entity->getParamByName('Материал (панель/кирпич)');
        switch($content){
            case "панель":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_PANEL);
                break;
            case "кирпич":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_BRICK);
                break;
        }
        return false;
    }
}
