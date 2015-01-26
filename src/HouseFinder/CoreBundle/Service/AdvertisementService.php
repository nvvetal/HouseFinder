<?php
namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\CoreBundle\Entity\AdvertisementPublish;
use HouseFinder\CoreBundle\Entity\AdvertisementRepository;
use HouseFinder\CoreBundle\Entity\DataContainer;
use HouseFinder\CoreBundle\Entity\Pager\AdvertisementPager;
use HouseFinder\CoreBundle\Entity\Room;
use HouseFinder\StorageBundle\Service\ImageService;

class AdvertisementService
{
    protected $container;
    /** @var  EntityManager $em */
    protected $em;
    /** @var  AdvertisementRepository $repo */
    protected $repo;

    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->get('Doctrine')->getManager();
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:Advertisement');
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
     * @return string
     */
    public function getFirstPhotoUrl(Advertisement $advertisement)
    {
        $urls = $this->getPhotoURLs($advertisement);
        $url = '';
        if (!is_null($urls) && count($urls) > 0) {
            $url = $urls[0];
        }
        return $url;
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
     * @return AdvertisementPager
     */
    public function searchAdvertisements(DataContainer $class)
    {
        return $this->repo->search($class);
    }

    /**
     * @param DataContainer $class
     * @return array
     */
    public function getAdvertisementsForMap(DataContainer $class)
    {
       return $this->repo->findByFresh($class);
    }

    /**
     * @param Advertisement $advertisement
     * @param $raw
     */
    public function fillAdvertisementPhotosByRaw(Advertisement &$advertisement, $raw)
    {
        if(isset($raw['data']['photo']) && count($raw['data']['photo']) > 0){
            foreach($raw['data']['photo'] as $photoData){
                if(empty($photoData)) continue;
                $photo = new AdvertisementPhoto();
                $photo->setUrl($photoData);
                $advertisement->addPhoto($photo);
            }
        }
    }

    /**
     * @param Advertisement $advertisement
     * @param \DateTime $created
     * @return AdvertisementPublish|null
     */
    public function findPublish(Advertisement $advertisement, \DateTime $created)
    {
        return $this->repo->findAdvertisementPublish($advertisement, $created);
    }

    /**
     * @param Advertisement $advertisement
     * @param Advertisement $source
     * @param \DateTime $created
     * @return AdvertisementPublish
     */
    public function createPublish(Advertisement $advertisement, Advertisement $source, \DateTime $created)
    {
        $publish = new AdvertisementPublish();
        $publish->setAdvertisement($advertisement);
        $publish->setPrice($source->getPrice());
        $publish->setCurrency($source->getCurrency());
        $publish->setCreated($created);
        $this->em->persist($publish);
        $this->em->flush($publish);
        return $publish;
    }
}