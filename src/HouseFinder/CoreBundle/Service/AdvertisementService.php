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
            $photoUrl = '';
            /**
             * @var $advertisement Advertisement
             */
            $photos = $advertisement->getPhotos();
            if (!is_null($photos) && count($photos) > 0) {
                /**
                 * @var $imageService ImageService
                 */
                $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
                $photoUrl = $imageService->getURL($photos[0]);
            }
            $data['items'][] = array(
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
        return $data;
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
        return array(
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
            'photos'        => $this->getPhotoURLs($advertisement),
            'rooms'         => $this->getRooms($advertisement),
            'roomsLiving'   => count($advertisement->getLivingRooms()),
            'house'         => (!is_null($advertisement->getHouse()) && $advertisement->getHouse()->isCorrect() ) ? $advertisement->getHouse() : NULL,
        );
    }

}