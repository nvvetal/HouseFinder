<?php
namespace HouseFinder\CoreBundle\Service;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementRepository;
use HouseFinder\CoreBundle\Entity\DataContainer;
use HouseFinder\CoreBundle\Entity\Room;

class AdvertisementService
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param Advertisement $advertisement
     * @return array
     */
    public function getPhotoURLs(Advertisement $advertisement)
    {
        $photos = $advertisement->getPhotos();
        $urls = array();
        if(is_null($photos) && count($photos) == 0) return $urls;
        /** @var $imageService ImageService */
        $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
        foreach($photos as $photo){
            $urls[] = $imageService->getURL($photo);
        }
        return $urls;
    }

    /**
     * @param Advertisement $advertisement
     * @return array
     */
    public function getRooms(Advertisement $advertisement)
    {
        $rooms = array();
        $aRooms = $advertisement->getRooms();
        if(is_null($aRooms) || count($aRooms) == 0) return $rooms;
        foreach($aRooms as $room){
            /** @var Room $room */
            $rooms[] = array(
                'id'    => $room->getId(),
                'space' => $room->getSpace(),
                'type'  => $room->getType(),
            );
        }
        return $rooms;
    }

    /**
     * @param DataContainer $class
     * @return array
     */
    public function getAdvertisementsREST(DataContainer $class)
    {
        $em = $this->container->get('Doctrine')->getManager();
        /** @var AdvertisementRepository $advertisementsRepo */
        $advertisementsRepo = $em->getRepository('HouseFinderCoreBundle:Advertisement');
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        $advertisements = $advertisementsRepo->search($class);
        $data = array(
            'pages' => $advertisements['pages'],
            'count' => $advertisements['count'],
        );
        foreach ($advertisements['items'] as $advertisement) {
            $data['items'][] = $this->getAdvertisementREST($advertisement);
        }
        return $data;
    }

    /**
     * @param DataContainer $class
     * @return array
     */
    public function getAdvertisementsForMapREST(DataContainer $class)
    {
        $em = $this->container->get('Doctrine')->getManager();
        /** @var AdvertisementRepository $advertisementsRepo */
        $advertisementsRepo = $em->getRepository('HouseFinderCoreBundle:Advertisement');
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        $advertisements = $advertisementsRepo->findByFresh($class);
        $data = array();
        foreach ($advertisements as $advertisement) {
            $data['items'][] = $this->getAdvertisementREST($advertisement);
        }
        return $data;
    }

    public function getAdvertisementREST(Advertisement $advertisement)
    {
        $photoUrls = $this->getPhotoURLs($advertisement);
        $photoUrl = '';
        if (!is_null($photoUrls) && count($photoUrls) > 0) {
            $photoUrl = $photoUrls[0];
        }
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        return array(
            'id' => $advertisement->getId(),
            'userId' => $advertisement->getUser()->getId(),
            'name' => $advertisement->getName(),
            'price' => $advertisement->getPrice(),
            'currency' => $advertisement->getCurrency(),
            'photo' => $photoUrl,
            'lastDate' => $advertisement->getLastUpdated()->format('Y-m-d H:i'),
            'address' => $addressService->getAddressREST($advertisement->getAddress()),
        );
    }

    /**
     * @param Advertisement $advertisement
     * @return array
     */
    public function getAdvertisementFullREST(Advertisement $advertisement)
    {
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        /** @var UserService $userService */
        $userService = $this->container->get('housefinder.service.user');
        /** @var HouseService $houseService */
        $houseService = $this->container->get('housefinder.service.house');

        $data = array(
            'id'            => $advertisement->getId(),
            'name'          => $advertisement->getName(),
            'description'   => trim($advertisement->getDescription()),
            'type'          => $advertisement->getType(),
            'rentType'      => $advertisement->getRentType(),
            'rentStartDate' => is_null($advertisement->getRentStartDate()) ? null : $advertisement->getRentStartDate()->format('Y-m-d'),
            'houseType'     => $advertisement->getHouseType(),
            'price'         => $advertisement->getPrice(),
            'currency'      => $advertisement->getCurrency(),
            'fullSpace'     => $advertisement->getFullSpace(),
            'livingSpace'   => $advertisement->getLivingSpace(),
            'level'         => $advertisement->getLevel(),
            'maxLevels'     => $advertisement->getMaxLevels(),
            'wallType'      => $advertisement->getWallType(),
            'brickType'     => $advertisement->getBrickType(),
            'heatingType'   => $advertisement->getHeatingType(),
            'special'       => $advertisement->getSpecial(),
            'created'       => $advertisement->getCreated()->format('d/m/Y H:i:s'),
            'owner'         => $userService->getUserREST($advertisement->getUser()),
            'address'       => $addressService->getAddressREST($advertisement->getAddress()),
            'house'         => $houseService->getHouseByAddressREST($advertisement->getAddress()),
            'photos'        => $this->getPhotoURLs($advertisement),
            'rooms'         => $this->getRooms($advertisement),
            'roomsLiving'   => count($advertisement->getLivingRooms()),
        );

        return $data;
    }

}