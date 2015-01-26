<?php
namespace HouseFinder\ParserBundle\Parser;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\CoreBundle\Entity\AdvertisementSlando;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Service\Slando\AdvertisementSlandoService;
use HouseFinder\CoreBundle\Service\Slando\UserSlandoService;
use HouseFinder\CoreBundle\Service\UserService;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;
use HouseFinder\ParserBundle\Service\SlandoService;
use phpOCR\cOCR;
use Symfony\Component\DomCrawler\Crawler;


class SlandoParser extends BaseParser
{
    /**
     * @param Crawler $crawler
     * @return mixed|void
     */
    protected function fetchLinks(Crawler $crawler)
    {
        $links = array();
        try {
            $links = $crawler->filter('a.detailsLink')->each(function (Crawler $node, $i) {
                $text = $node->text();
                $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
                if (empty($text)) return false;
                $url = $node->attr('href');
                $sourceHash = '';
                if(preg_match("/(\w+)\.html/i", $url, $matches)){
                    $sourceHash = $matches[1];
                }
                return array(
                    'title'         => $text,
                    'url'           => $url,
                    'sourceHash'    => $sourceHash,
                );
            });
        }catch(\Exception $e){
            echo "Cant get details: ".$e->getMessage();
        }
        return $links;
    }

    /**
     * @param array $link
     * @return SlandoParserEntity|mixed
     */
    protected function fetchPageByLink(array $link){

        /** @var SlandoService $service */
        $service = $this->container->get('housefinder.parser.service.slando');
        $crawlerPage = $service->getPageCrawler($link['url']);
        try {
            $parserEntity = $this->parsePageDomCrawler($crawlerPage);
            $parserEntity->setName($link['title']);
            $parserEntity->setUrl($link['url']);
            $parserEntity->setSourceHash($link['sourceHash']);
            return array(
              'res' => 'ok',
              'entity' => $parserEntity,
            );
        }catch(\Exception $e){
            echo $err = "Error on page {$link['url']} :".$e->getMessage();
            return array(
              'res'     => 'error',
              'message' => $err,
            );
        }
    }

    /**
     * @param Crawler $crawler
     * @return SlandoParserEntity
     */
    protected function parsePageDomCrawler(Crawler $crawler)
    {
        /** @var UserService $userService */
        $userService = $this->container->get('housefinder.service.user');
        /** @var UserSlandoService $userSlandoService */
        $userSlandoService = $this->container->get('housefinder.service.slando.user');
        $slandoParserEntity = new SlandoParserEntity();
        $slandoParserEntity->setType($this->advertisementType);
        $slandoParserEntity->setParams($this->getParams($crawler));
        $slandoParserEntity->setDescription($crawler->filter('#textContent p.large')->text());
        $priceData = $this->getPriceData($crawler->filter("div.pricelabel.tcenter strong")->text());
        $slandoParserEntity->setPrice($priceData['price']);
        $slandoParserEntity->setCurrency($priceData['currency']);
        $address = $crawler->filter("div.address p")->text();
        $slandoParserEntity->setAddress(trim($address, " \n\t\r\0\x0B\xC2\xA0"));
        $slandoParserEntity->setPhotos($crawler->filter("div.photo-glow img")->extract(array('src')));
        $owner = $this->getOwnerData($crawler);
        $slandoParserEntity->setOwnerId($owner['id']);
        $slandoParserEntity->setOwnerHash($owner['hash']);
        $slandoParserEntity->setOwnerName($owner['name']);
        $slandoParserEntity->setOwnerUrl($owner['url']);
        $this->fillUserType($slandoParserEntity);
        $created = $this->getCreatedDateTimeAndSourceId($crawler);
        $slandoParserEntity->setSourceId($created['sourceID']);
        $slandoParserEntity->setCreatedDateTime(new \DateTime($created['createdDateTime']));

        if($this->advertisementType == Advertisement::TYPE_RENT){
            $this->fillRent($slandoParserEntity);
            $this->fillRentStartDate($slandoParserEntity);
        }
        $this->fillRooms($slandoParserEntity);
        $this->fillFullSpace($slandoParserEntity);
        $this->fillLivingSpace($slandoParserEntity);
        $this->fillLevel($slandoParserEntity);

        $this->fillMaxLevels($slandoParserEntity);
        $this->fillWallType($slandoParserEntity);
        $this->fillHouseType($slandoParserEntity);

        //please do that at end
        $user = $userService->getUserByRaw($userSlandoService->getUserHash($slandoParserEntity), $slandoParserEntity);
        if(is_null($user) || is_null($user->getPhoneUpdated()) || $user->getPhoneUpdated()->getTimestamp() + 15*24*3600 < time()){
            $h = is_null($user) ? $slandoParserEntity->getOwnerHash() : $user->getId();
            $slandoParserEntity->setPhones($this->getPhones($h, $crawler));
        }
        return $slandoParserEntity;
    }

    /**
     * @param SlandoParserEntity $entity
     * @return bool
     */
    private function fillUserType(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Объявление от', $entity->getParams());
        if(is_null($content)) return false;
        switch($content){
            case "Агентства":
                $entity->setOwnerType(UserSlando::TYPE_REALTOR);
                break;
        }
        return false;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getParams(Crawler $crawler)
    {
        return $crawler->filter('div.pding5_10')->each(function (Crawler $node, $i) {
            $text = $node->text();
            $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
            $data = explode(":", $text, 2);
            $data[1] = trim($data[1], " \n\t\r\0\x0B\xC2\xA0");
            return $data;
        });

    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getPhones($userHash, Crawler $crawler)
    {
        $phones = array();
        $self = &$this;
        $res = $crawler->filter("li[data-rel=phone]")->eq(0)->extract(array('class'));
        if (isset($res[0]) && preg_match("/\{.*\}/", $res[0], $matches)) {

            try {
                $phoneJSON = json_decode(strtr($matches[0], "'", '"'), true);
                $phoneHash = $phoneJSON['id'];

                /** @var $service SlandoService */
                $service = $this->container->get('housefinder.parser.service.slando');
                $phoneURL = 'http://zhitomir.zht.olx.ua/ajax/misc/contact/phone/' . $phoneHash . '/white/';
                sleep(1);
                $phoneContent = implode('', json_decode($service->getPageContent($phoneURL), true));
                $cr = new Crawler();
                $cr->addHtmlContent($phoneContent);
                $phones = $cr->filter('span.block')->each(function (Crawler $node, $i) use ($self) {
                    return $self->normalizePhone($node->text());
                });
                /*
                 * Used when numbers is in IMAGE
                list(, $phoneContent,) = explode('"', $phoneContent);
                $phoneFile = $service->getFile($phoneContent);
                $template = cOCR::loadTemplate('slando');
                $img = cOCR::openImg($phoneFile);
                cOCR::setInfelicity(5);

                $data['phone'] = explode(",", preg_replace("/[^\d,]/", "", cOCR::defineImg(cOCR::$img, $template)));
                unlink($phoneFile);
                */

            }catch(\Exception $e){
                $this->logger->write('[id '.$userHash.'][error '.$e->getMessage().']', 'error_slando_parse_phone');
            }
            $this->logger->write('[id '.$userHash.'][data '.var_export(json_decode(json_encode($phones), true), true).']', 'slando_parse_page_data');
        }
        return $phones;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getOwnerData(Crawler $crawler)
    {
        $data = array(
            'name' => '',
            'url' => '',
            'hash' => '',
            'id' => '',
        );
        $data['name'] = $crawler->filter("p.userdetails span")->eq(0)->text();
        $data['url'] = '';
        $ownerURLs = $crawler->filter("#linkUserAds")->extract(array('href'));
        if(isset($ownerURLs[0])) $data['url'] = $ownerURLs[0];
        if(preg_match("/\/user\/(.*)\/$/i", $data['url'], $matches)){
            $data['hash'] = $matches[1];
            $data['id'] = $matches[1];
        }
        return $data;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    private function getCreatedDateTimeAndSourceId(Crawler $crawler)
    {
        $data = array();
        $data['createdText'] = $crawler->filter("div.offerheadinner p small span")->text();
        $data['createdText'] = str_replace('Добавлено: в', '', $data['createdText']);
        $data['createdText'] = trim($data['createdText'], " \n\t\r\0\x0B\xC2\xA0");
        //13:07, 21 декабря 2013, номер: 121717607
        if(preg_match("/(\d+)\:(\d+)\, (\d+) (\w+) (\d+)\, номер\: (\d+)/iu", $data['createdText'], $matches)){
            $data['sourceID'] = $matches[6];
            $data['createdDateTime'] = $matches[5].'-'.$this->getMonthByRussianName($matches[4]).'-'.$matches[3].' '.$matches[1].':'.$matches[2].':00';
        }
        return $data;
    }

    /**
     * @param SlandoParserEntity $raw
     * @return AdvertisementSlando;
     */
    protected function getEntityByRAW($raw)
    {
        /** @var AdvertisementSlandoService $service */
        $service = $this->container->get('housefinder.service.slando.advertisement');
        return $service->fillByRaw($raw);
    }

    private function fillRooms(SlandoParserEntity &$entity)
    {
        $roomsCount = $this->searchSlandoParamByName('Количество комнат', $entity->getParams());
        if(is_null($roomsCount)) return false;
        $kitchenSpace = $this->getKitchenSpace($entity,  $entity->getParams());
        for($i = 0; $i < $roomsCount; $i++){
            //TODO: fill rooms from text parse somehow
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

    private function fillFullSpace(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Общая площадь', $entity->getParams());
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            $entity->setFullSpace($m[1]);
            return true;
        }
        return false;
    }

    private function fillLivingSpace(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Жилая площадь', $entity->getParams());
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            $entity->setLivingSpace($m[1]);
            return true;
        }
        return false;
    }

    private function getKitchenSpace(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Площадь кухни', $entity->getParams());
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            return $m[1];
        }
        return false;
    }

    private function fillLevel(SlandoParserEntity &$entity)
    {
        $level = $this->searchSlandoParamByName('Этаж', $entity->getParams());
        if(is_null($level)) return false;
        $entity->setLevel($level);
        return false;
    }

    private function fillMaxLevels(SlandoParserEntity &$entity)
    {
        $maxLevels = $this->searchSlandoParamByName('Этажность дома', $entity->getParams());
        if(is_null($maxLevels)) return false;
        $entity->setMaxLevels($maxLevels);
        return false;
    }

    /**
     * @param SlandoParserEntity $entity
     * @return bool
     */
    private function fillRent(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Тип аренды', $entity->getParams());
        if(is_null($content)) return false;
        $rentType = $this->getRentType($content);
        $entity->setRentType($rentType);
        return true;
    }

    private function fillHouseType(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Тип квартиры', $entity->getParams());
        if(is_null($content)) return false;
        switch($content)
        {
            case "Вторичный рынок":
                $entity->setHouseType(AdvertisementSlando::HOUSE_TYPE_OLD);
                break;
            case "Новостройки":
                $entity->setHouseType(AdvertisementSlando::HOUSE_TYPE_NEW);
                break;
        }
        return true;
    }

    private function fillRentStartDate(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Сдается с',  $entity->getParams());
        if(is_null($content)) return false;
        if(preg_match("/^(\d+) (\w+) (\d+)$/iu", $content, $matches)){
            $month = $this->getMonthByRussianName($matches[2]);
            $date = new \DateTime($matches[3].'-'.$month.'-'.$matches[1]);
            $entity->setRentStartDate($date);
        }
        return false;
    }

    private function fillWallType(SlandoParserEntity &$entity)
    {
        $content = $this->searchSlandoParamByName('Тип', $entity->getParams());
        switch($content){
            case "Панельный":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_PANEL);
                break;
            case "Кирпичный":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_BRICK);
                break;
            case "Монолитный":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_MONOLITH);
                break;
            case "Блочный":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_BLOCK);
                break;
            case "Деревянный":
                $entity->setWallType(AdvertisementSlando::WALL_TYPE_WOOD);
                break;
        }
        return false;
    }

    private function getMonthByRussianName($russianName)
    {
        $month = array(
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря',
        );
        $keys = array_keys($month, $russianName);
        return sprintf("%02d", $keys[0]+1);
    }

    private function getRentType($content)
    {
        $rentType = '';
        $content = mb_strtolower($content, 'UTF-8');
        switch($content){
            case "долгосрочная аренда квартир":
                $rentType = Advertisement::RENT_TYPE_LONG;
                break;

            case "квартиры посуточно":
                $rentType = Advertisement::RENT_TYPE_DAY;
                break;

            case "квартиры с почасовой оплатой":
                $rentType = Advertisement::RENT_TYPE_HOUR;
                break;
        }
        return $rentType;
    }


    private function searchSlandoParamByName($name, $params)
    {
        foreach ($params as $data) {
            if($data[0] == $name) return $data[1];
        }
        return NULL;
    }

    public function postParseText(Advertisement &$entity)
    {
        $txt = $entity->getDescription();
        if($entity->getWallType() == '') {
            $entity->setWallType($this->parseTextWallType($txt));
        }
        $space = $this->parseFullLiveKitchenSpace($txt);
        if(!is_null($space)){
            if(is_null($entity->getFullSpace())) $entity->setFullSpace($space['fullSpace']);
            if(is_null($entity->getLivingSpace())) {
                if(count($entity->getLivingRooms()) == 1){
                    $entity->setFirstLivingRoomSpace($space['livingSpace']);
                }
                $entity->setLivingSpace($space['livingSpace']);
            }
            $entity->setFirstKitchenSpace($space['kitchenSpace']);
        }
        if($entity->getHeatingType() == ''){
            $heatingIndependent = $this->parseTextIndependentHeating($txt);
            if($heatingIndependent) $entity->setHeatingType($heatingIndependent);
        }

        $levels = $this->parseLevels($txt);
        if(!is_null($levels)){
            if(is_null($entity->getLevel())) {
                $entity->setLevel($levels['level']);
            }
            if(is_null($entity->getMaxLevels())) {
                $entity->setMaxLevels($levels['maxLevels']);
            }
        }

        //parse special features

        if($this->parseTextBalcony($txt)) $entity->setSpecial('balcony', true);
        if($this->parseTextPhone($txt)) $entity->setSpecial('phone', true);
        if($this->parseTextVault($txt)) $entity->setSpecial('vault', true);
        if($this->parseTextGarage($txt)) $entity->setSpecial('garage', true);
        if($this->parseTextWindowPlastic($txt)) $entity->setSpecial('windowPlastic', true);
        if($this->parseTextConditioner($txt)) $entity->setSpecial('conditioner', true);
        if($this->parseTextGasBoiler($txt)) $entity->setSpecial('boilerGas', true);
        if($this->parseTextElectricalBoiler($txt)) $entity->setSpecial('boilerElectro', true);
        if($this->parseTextLaminate($txt)) $entity->setSpecial('laminate', true);
        if($this->parseTextParquet($txt)) $entity->setSpecial('parquet', true);
        if($this->parseTextWithDocuments($txt)) $entity->setSpecial('documents', true);
        if($this->parseTextFurniture($txt)) $entity->setSpecial('furniture', true);
        if($this->parseTextRefrigerator($txt)) $entity->setSpecial('refrigerator', true);
        if($this->parseTextCanTrade($txt)) $entity->setSpecial('trade', true);
        if($this->parseTextWCIndependent($txt)) $entity->setSpecial('wcIndependent', true);
        if($this->parseTextWCShared($txt)) $entity->setSpecial('wcShared', true);
        if($this->parseTextCounters($txt)) $entity->setSpecial('counters', true);
        if($this->parseTextArmoredDoor($txt)) $entity->setSpecial('doorArmored', true);
        if($this->parseTextHangar($txt)) $entity->setSpecial('hangar', true);
        if($this->parseTextLoggia($txt)) $entity->setSpecial('loggia', true);
        if($this->parseTextInternet($txt)) $entity->setSpecial('internet', true);
        if($this->parseTextPantry($txt)) $entity->setSpecial('pantry', true);
        if($this->parseTextFurnitureKitchenIntegrated($txt)) $entity->setSpecial('furnitureKitchen', true);
        if($this->parseTextNotCorner($txt)) $entity->setSpecial('notCorner', true);
        if($this->parseTextDoorsWindows($txt)) $entity->setSpecial('doorsWindows', true);
        if($this->parseTextIntercom($txt)) $entity->setSpecial('intercom', true);
        if($this->parseTextCableTV($txt)) $entity->setSpecial('cableTV', true);
        if($this->parseTextTV($txt)) $entity->setSpecial('TV', true);
        if($this->parseTextRoomsSeparated($txt)) $entity->setSpecial('roomSeparated', true);
        if($this->parseTextShower($txt)) $entity->setSpecial('shower', true);
        if($this->parseTextWashingMachine($txt)) $entity->setSpecial('washingMachine', true);
        if($this->parseTextMicrowave($txt)) $entity->setSpecial('microwave', true);
        if($this->parseTextKitchenHood($txt)) $entity->setSpecial('kitchenHood', true);
        if($this->parseTextRoomsAdjacent($txt)) $entity->setSpecial('roomsAdjacent', true);

    }

    protected function getPriceData($content)
    {
        $priceData = array(
            'price' => 0,
            'currency' => Advertisement::CURRENCY_UAH
        );
        $content = str_replace(" ", "", $content);
        if(preg_match("~^([\d\s]+)(.*?)\s*$~", $content, $matches)){
            $priceData = array(
                'price'     => str_replace(" ", '', $matches[1]),
                'currency'  => $this->getCurrencyBySign($matches[2]),
            );
        }
        return $priceData;
    }
}
