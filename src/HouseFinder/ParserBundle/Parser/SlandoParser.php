<?php
namespace HouseFinder\ParserBundle\Parser;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\CoreBundle\Entity\AdvertisementSlando;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Entity\UserPhone;
use HouseFinder\ParserBundle\Parser\BaseParser;
use HouseFinder\ParserBundle\Service\SlandoService;
use phpOCR\cOCR;
use Symfony\Component\DomCrawler\Crawler;


class SlandoParser extends BaseParser
{
    /**
     * @param Crawler $crawler
     * @return mixed|void
     */
    protected function parseListDomCrawler(Crawler $crawler)
    {
        try {
            $links = $crawler->filter('a.detailsLink')->each(function (Crawler $node, $i) {
                $text = $node->text();
                $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
                if (empty($text)) return false;
                $url = $node->attr('href');
                $sourceHash = '';
                if(preg_match("/(\w+)\.html$/i", $url, $matches)){
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
        $pages = array();
        $dublicates = 0;
        foreach ($links as $key => $link) {
            if ($link === false) continue;
            /**
             * @var $em EntityManager
             */
            $em = $this->container->get('Doctrine')->getManager();
            $find = $em->getRepository('HouseFinderCoreBundle:AdvertisementSlando')->findOneBy(array('sourceHash' => $link['sourceHash']));
            if(!is_null($find)) $dublicates++;
            if($dublicates >= 3) {
                echo "Dublicates found next...\n";
                break;
            }
            $service = $this->container->get('housefinder.parser.service.slando');
            $crawlerPage = $service->getPageCrawler($link['url']);
            try {
                $pageData = $this->parsePageDomCrawler($crawlerPage);
                $pages[$key] = $link;
                $pages[$key]['data'] = $pageData;
            }catch(\Exception $e){
                echo "Error on page {$link['url']} :".$e->getMessage();
            }
        }
        return $pages;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    protected function parsePageDomCrawler(Crawler $crawler)
    {
        //header('Content-Type: text/html; charset=utf-8');
        $data = array();
        $data['params'] = $crawler->filter('div.pding5_10')->each(function (Crawler $node, $i) {
            $text = $node->text();
            $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
            $data = explode(":", $text, 2);
            $data[1] = trim($data[1], " \n\t\r\0\x0B\xC2\xA0");
            return $data;
        });
        $data['text'] = $crawler->filter('#textContent p.large')->text();
        $data['price'] = $crawler->filter("div.pricelabel.tcenter strong")->text();
        $data['address'] = $crawler->filter("div.address p")->text();
        $data['address'] = trim($data['address'], " \n\t\r\0\x0B\xC2\xA0");
        $data['photo'] = $crawler->filter("div.photo-glow img")->extract(array('src'));
        $data['ownerName'] = $crawler->filter("p.userdetails span")->eq(0)->text();
        $data['ownerUrl'] = '';
        $ownerURLs = $crawler->filter("#linkUserAds")->extract(array('href'));
        if(isset($ownerURLs[0])) $data['ownerUrl'] = $ownerURLs[0];
        if(preg_match("/\/user\/(.*)\/$/i", $data['ownerUrl'], $matches)){
            $data['ownerHash'] = $matches[1];
            $data['ownerId'] = $matches[1];
        }
        $data['createdText'] = $crawler->filter("div.offerheadinner p small span")->text();
        $data['createdText'] = str_replace('Добавлено: в', '', $data['createdText']);
        $data['createdText'] = trim($data['createdText'], " \n\t\r\0\x0B\xC2\xA0");
        //13:07, 21 декабря 2013, номер: 121717607
        if(preg_match("/(\d+)\:(\d+)\, (\d+) (\w+) (\d+)\, номер\: (\d+)/iu", $data['createdText'], $matches)){
            $data['sourceID'] = $matches[6];
            $data['createdDateTime'] = $matches[5].'-'.$this->getMonthByRussianName($matches[4]).'-'.$matches[3].' '.$matches[1].':'.$matches[2].':00';
        }
        $phone = $crawler->filter("span[data-rel=phone]")->eq(0)->extract(array('class'))[0];
        if (preg_match("/\{.*\}/", $phone, $matches)) {
            //var_dump($matches);
            $phoneJSON = json_decode(strtr($matches[0], "'", '"'), true);
            $phoneHash = $phoneJSON['id'];
            /** @var $service SlandoService */
            $service = $this->container->get('housefinder.parser.service.slando');
            $phoneURL = 'http://slando.ua/ajax/misc/contact/phone/' . $phoneHash . '/';
            sleep(3);
            $phoneContent = json_decode($service->getPageContent($phoneURL), true);
            list(, $phoneContent,) = explode('"', $phoneContent);
            $phoneFile = $service->getFile($phoneContent);
            $template = cOCR::loadTemplate('slando');
            $img = cOCR::openImg($phoneFile);
            cOCR::setInfelicity(5);
            $data['phone'] = explode(",", preg_replace("/[^\d,]/", "", cOCR::defineImg(cOCR::$img, $template)));
            unlink($phoneFile);
        }
        /*
                echo "<pre>";
                var_dump($data);
                exit;
        */
        return $data;
    }

    /**
     * @param string|string $raw
     * @return AdvertisementSlando;
     */
    protected function getEntityByRAW($raw)
    {
        //echo "<pre>";
        //var_dump($raw);
        $userSlando = $this->getUser($raw);
        $address = $this->getAddress($raw['data']['address']);
        $entity = new AdvertisementSlando();
        $entity->setUser($userSlando);
        $entity->setAddress($address);
        $entity->setName($raw['title']);
        $entity->setDescription($raw['data']['text']);
        $entity->setSourceId($raw['data']['sourceID']);
        $entity->setSourceHash($raw['sourceHash']);
        $entity->setSourceURL($raw['url']);
        $priceData = $this->getPriceData($raw['data']['price']);
        //var_dump($priceData);
        $entity->setPrice($priceData['price']);
        $entity->setCurrency($priceData['currency']);
        $entity->setType($this->advertisementType);
        $dt = new \DateTime($raw['data']['createdDateTime']);
        $entity->setCreated($dt);
        if($this->advertisementType == Advertisement::TYPE_RENT){
            $this->fillRent($entity, $raw['data']['params']);
            $this->fillRentStartDate($entity, $raw['data']['params']);
        }
        $this->fillRooms($entity, $raw['data']['params']);
        $this->fillFullSpace($entity, $raw['data']['params']);
        $this->fillLivingSpace($entity, $raw['data']['params']);
        $this->fillLevel($entity, $raw['data']['params']);
        $this->fillMaxLevels($entity, $raw['data']['params']);
        $this->fillWallType($entity, $raw['data']['params']);
        $this->fillHouseType($entity, $raw['data']['params']);

        if(isset($raw['data']['photo']) && count($raw['data']['photo']) > 0){
            foreach($raw['data']['photo'] as $photoData){
                if(empty($photoData)) continue;
                $photo = new AdvertisementPhoto();
                $photo->setUrl($photoData);
                $photo->setAdvertisement($entity);
                $entity->addPhoto($photo);
            }
        }
        return $entity;
    }

    private function fillRooms(AdvertisementSlando &$entity, $params)
    {
        $roomsCount = $this->searchSlandoParamByName('Количество комнат', $params);
        if(is_null($roomsCount)) return false;
        $kitchenSpace = $this->getKitchenSpace($entity, $params);
        for($i = 0; $i < $roomsCount; $i++){
            //TODO: fill rooms from text parse somehow
            $room = new Room();
            $room->setType(Room::TYPE_ROOM);
            $room->setAdvertisement($entity);
            $entity->addRoom($room);
        }
        if($kitchenSpace !== false){
            $room = new Room();
            $room->setType(Room::TYPE_KITCHEN);
            $room->setSpace($kitchenSpace);
            $room->setAdvertisement($entity);
            $entity->addRoom($room);
        }
        return true;
    }

    private function fillFullSpace(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Общая площадь', $params);
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            $entity->setFullSpace($m[1]);
            return true;
        }
        return false;
    }

    private function fillLivingSpace(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Жилая площадь', $params);
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            $entity->setLivingSpace($m[1]);
            return true;
        }
        return false;
    }

    private function getKitchenSpace(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Площадь кухни', $params);
        if(is_null($content)) return false;
        if(preg_match("/^(\d+)/iu", $content, $m)){
            return $m[1];
        }
        return false;
    }

    private function fillLevel(AdvertisementSlando &$entity, $params)
    {
        $level = $this->searchSlandoParamByName('Этаж', $params);
        if(is_null($level)) return false;
        $entity->setLevel($level);
        return false;
    }

    private function fillMaxLevels(AdvertisementSlando &$entity, $params)
    {
        $maxLevels = $this->searchSlandoParamByName('Этажность дома', $params);
        if(is_null($maxLevels)) return false;
        $entity->setMaxLevels($maxLevels);
        return false;
    }



    private function fillRent(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Тип аренды', $params);
        if(is_null($content)) return false;
        $rentType = $this->getRentType($content);
        $entity->setRentType($rentType);
        return true;
    }

    private function fillHouseType(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Тип квартиры', $params);
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

    private function fillRentStartDate(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Сдается с', $params);
        if(is_null($content)) return false;
        if(preg_match("/^(\d+) (\w+) (\d+)$/iu", $content, $matches)){
            $month = $this->getMonthByRussianName($matches[2]);
            $date = new \DateTime($matches[3].'-'.$month.'-'.$matches[1]);
            $entity->setRentStartDate($date);
        }
        return false;
    }

    private function fillUserType(UserSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Объявление от', $params);
        if(is_null($content)) return false;
        switch($content){
            case "Агентства":
                $entity->setType(UserSlando::TYPE_REALTOR);
                break;
        }
        return false;
    }

    private function fillWallType(AdvertisementSlando &$entity, $params)
    {
        $content = $this->searchSlandoParamByName('Тип', $params);
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

    private function getUser($raw)
    {
        /** @var $em EntityManager */
        $em = $this->container->get('Doctrine')->getManager();
        $userHash = isset($raw['data']['ownerHash']) ? $raw['data']['ownerHash'] : '';
        $userURL = isset($raw['data']['ownerUrl']) ? $raw['data']['ownerUrl'] : '';
        $UserSlando = null;
        if(!empty($userHash)){
            $UserSlando = $em->getRepository('HouseFinderCoreBundle:UserSlando')
                ->findOneBy(array('sourceHash' => $userHash));
        }elseif(count($raw['data']['phone']) > 0){
            $UserSlandoPhone = null;
            foreach ($raw['data']['phone'] as $msisdn){
                $UserSlandoPhone = $em->getRepository('HouseFinderCoreBundle:UserPhone')
                    ->findOneBy(array('msisdn' => $msisdn));
                if(!is_null($UserSlandoPhone)) break;
            }
            if(is_null($UserSlandoPhone)){
                $userHash = 'byPhone:'.$msisdn;
            }else{
                $UserSlando = $UserSlandoPhone->getUser();
            }
        }

        if(is_null($UserSlando)){
            $UserSlando = new UserSlando();
            $UserSlandoName = $raw['data']['ownerName'].'@'.$userHash.'@slando';
            $UserSlando->setUsername($UserSlandoName);
            $UserSlando->setUsernameCanonical($UserSlandoName);
            $UserSlando->setEmail($UserSlandoName);
            $UserSlando->setEmailCanonical($UserSlandoName);
            $UserSlando->setSourceHash($userHash);
            $UserSlando->setSourceURL($userURL);
            $UserSlando->setPassword(md5(time()));
            $UserSlando->setLocked(true);
            $UserSlando->setExpired(true);
            $UserSlando->setRoles(array());
            $UserSlando->setCredentialsExpired(true);
            $this->fillUserType($UserSlando, $raw['data']['params']);
            $em->persist($UserSlando);
            $em->flush();
            foreach ($raw['data']['phone'] as $msisdn){
                $phone = new UserPhone();
                $phone->setMsisdn($msisdn);
                $phone->setUser($UserSlando);
                $em->persist($phone);
                $em->flush();
            }
        }
        return $UserSlando;
    }

    private function getAddress($content)
    {
        //TODO: fix address - parse full path
        /** @var $em EntityManager */
        $em = $this->container->get('Doctrine')->getManager();
        $address = $em->getRepository('HouseFinderCoreBundle:Address')->findOneBy(array('address'=>$content));
        if(is_null($address)){
            $address = new Address();
            $address->setAddress($content);
            $em->persist($address);
            $em->flush();
        }
        return $address;
    }

    private function getPriceData($content)
    {
        $priceData = array(
            'price' => 0,
            'currency' => Advertisement::CURRENCY_UAH
        );
        $content = str_replace(" ", "", $content);
        if(preg_match("~^([\d\s]+)(.*?)\s*$~", $content, $matches)){
            $priceData = array(
                'price'     => str_replace(" ", '', $matches[1]),
                'currency'  => $this->getCurrencyBySlando($matches[2]),
            );
        }
        return $priceData;
    }

    private function getCurrencyBySlando($slandoCurrency)
    {
        $slandoCurrency = mb_strtolower($slandoCurrency, 'UTF-8');
        $currency = '';
        switch($slandoCurrency)
        {
            case "грн.":
                $currency = Advertisement::CURRENCY_UAH;
                break;
            case "$":
                $currency = Advertisement::CURRENCY_USD;
                break;
            case "€":
                $currency = Advertisement::CURRENCY_EUR;
                break;
        }
        return $currency;
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
        if($entity->getWallType() == '') {
            $entity->setWallType($this->parseTextWallType($entity->getDescription()));
        }

        $space = $this->parseFullLiveKitchenSpace($entity->getDescription());
        if(!is_null($space)){
            if(is_null($entity->getFullSpace())) $entity->setFullSpace($space['fullSpace']);
            if(is_null($entity->getLivingSpace())) $entity->setLivingSpace($space['livingSpace']);
            $kitchens = $entity->getKitchens();
            if(is_null($kitchens)){
                $kitchen = new Room();
                $kitchen->setAdvertisement($entity);
                $kitchen->setType(Room::TYPE_KITCHEN);
                $kitchen->setSpace($space['kitchenSpace']);
            }elseif(is_null($kitchens[0]->getSpace())){
                $kitchens[0]->setSpace($space['kitchenSpace']);
            }
        }
        if($entity->getHeatingType() == ''){
            $heatingIndependent = $this->parseTextIndependentHeating($entity->getDescription());
            if($heatingIndependent) $entity->setHeatingType($heatingIndependent);
        }
    }
}
