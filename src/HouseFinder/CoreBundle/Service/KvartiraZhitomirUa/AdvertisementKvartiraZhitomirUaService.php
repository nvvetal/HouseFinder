<?php
namespace HouseFinder\CoreBundle\Service\KvartiraZhitomirUa;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementKvartiraZhitomirUa;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\CoreBundle\Entity\AdvertisementSlando;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\CoreBundle\Service\AdvertisementService;
use HouseFinder\CoreBundle\Service\HouseService;
use HouseFinder\CoreBundle\Service\KvartiraZhitomirUa\UserKvartiraZhitomirUaService;
use HouseFinder\ParserBundle\Entity\KvartiraZhitomirUa\KvartiraZhitomirUaParserEntity;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;
use HouseFinder\ParserBundle\Service\AddressService;
use HouseFinder\ParserBundle\Service\KvartiraZhitomirUaService;


class AdvertisementKvartiraZhitomirUaService
{
    protected $container;
    /** @var EntityManager $em */
    protected $em;
    /** @var EntityRepository $repo */
    protected $repo;
    /** @var UserKvartiraZhitomirUaService $userKvartiraZhitomirUaService */
    protected $userKvartiraZhitomirUaService;
    /** @var AddressService $addressService */
    protected $addressService;
    /** @var HouseService $houseService */
    protected $houseService;
    /** @var AdvertisementService $advertisementService */
    protected $advertisementService;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:AdvertisementKvartiraZhitomirUa');
        $this->userKvartiraZhitomirUaService = $this->container->get('housefinder.service.kvartira_zhitomir_ua.user');
        /** @var AddressService $addressService */
        $this->addressService = $this->container->get('housefinder.parser.service.address');
        /** @var HouseService $houseService */
        $this->houseService = $this->container->get('housefinder.service.house');
        /** @var AdvertisementService $advertisementService */
        $this->advertisementService = $this->container->get('housefinder.service.advertisement');
    }

    /**
     * @param string $hash
     * @return AdvertisementSlando
     */
    public function getAdvertisementBySourceHash($hash)
    {
        return $this->repo->findOneBy(array(
            'sourceHash' => $hash
        ));
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return AdvertisementKvartiraZhitomirUa|null
     */
    public function fillByRaw(KvartiraZhitomirUaParserEntity $raw)
    {
        $user = $this->userKvartiraZhitomirUaService->getOrCreateUserByRaw($raw);
        if(is_null($raw->getAddress())) return NULL;
        $address = $this->addressService->getAddress($raw->getAddress());
        if(is_null($address)) return NULL;
        $entity = new AdvertisementKvartiraZhitomirUa();
        $entity->setUser($user);
        $entity->setAddress($address);
        $house = $this->houseService->getHouseByAddress($address);
        if(!is_null($house)) $entity->setHouse($house);
        $entity->setName($raw->getName());
        $entity->setDescription($raw->getDescription());
        $entity->setSourceId($raw->getSourceHash());
        $entity->setSourceHash($raw->getSourceHash());
        $entity->setSourceURL($raw->getSourceURL());
        $entity->setPrice($raw->getPrice());
        $entity->setCurrency($raw->getCurrency());
        $entity->setType($raw->getType());
        $entity->setCreated($raw->getCreatedDateTime());
        $entity->setUpdated($raw->getUpdatedDateTime());
        if($entity->isRent()){
            $entity->setRentType($raw->getRentType());
            if(!is_null($raw->getRentStartDate())) $entity->setRentStartDate($raw->getRentStartDate());
        }
        $this->fillRoomsByRaw($entity, $raw);
        $entity->setFullSpace($raw->getFullSpace());
        $entity->setLivingSpace($raw->getLivingSpace());
        $entity->setLevel($raw->getLevel());
        $entity->setParams($raw->getParams());
        if(is_null($entity->getHouse())){
            $entity->setMaxLevels($raw->getMaxLevels());
            $entity->setWallType($raw->getWallType());
            $entity->setHouseType($raw->getHouseType());
        }
        $this->fillPhotosByRaw($entity, $raw);
        return $entity;
    }


    /**
     * @param AdvertisementKvartiraZhitomirUa $entity
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return bool
     */
    private function fillRoomsByRaw(AdvertisementKvartiraZhitomirUa &$entity, KvartiraZhitomirUaParserEntity $raw)
    {
        if(count($raw->getRooms()) == 0) return false;
        /** @var Room $room */
        foreach($raw->getRooms() as $room){
            $entity->addRoom($room);
        }
        return true;
    }

    /**
     * @param AdvertisementKvartiraZhitomirUa $entity
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return bool
     */
    private function fillPhotosByRaw(AdvertisementKvartiraZhitomirUa &$entity, KvartiraZhitomirUaParserEntity $raw)
    {
        if(count($raw->getPhotos()) == 0) return false;
        foreach($raw->getPhotos() as $photoData){
            if(empty($photoData)) continue;
            $photo = new AdvertisementPhoto();
            $photo->setUrl($photoData);
            $entity->addPhoto($photo);
        }
        return true;
    }
}
