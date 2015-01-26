<?php

namespace HouseFinder\ParserBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementExternal;
use HouseFinder\CoreBundle\Entity\AdvertisementKvartiraZhitomirUa;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\CoreBundle\Entity\AdvertisementSlando;
use HouseFinder\CoreBundle\Service\AdvertisementService;
use HouseFinder\CoreBundle\Service\ExifService;
use HouseFinder\CoreBundle\Service\KvartiraZhitomirUa\AdvertisementKvartiraZhitomirUaService;
use HouseFinder\CoreBundle\Service\Slando\AdvertisementSlandoService;
use HouseFinder\ParserBundle\Parser\KvartiraZhitomirUaParser;
use HouseFinder\ParserBundle\Parser\SlandoParser;
use HouseFinder\StorageBundle\Service\ImageService;

class KvartiraZhitomirUaService extends BaseAdvertisementService
{
    /**
     * @param AdvertisementExternal $entity
     * @return mixed
     */
    function saveAdvertisementEntity(AdvertisementExternal &$entity)
    {
        /** @var $em EntityManager */
        $em = $this->container->get('Doctrine')->getManager();
        /** @var AdvertisementKvartiraZhitomirUaService $advertisementKvartiraZhitomirUaService */
        $advertisementKvartiraZhitomirUaService = $this->container->get('housefinder.service.kvartira_zhitomir_ua.advertisement');
        try {
            /** @var $oldEntity AdvertisementKvartiraZhitomirUa */
            $oldEntity = $advertisementKvartiraZhitomirUaService->getAdvertisementBySourceHash($entity->getSourceHash());
            $currentEntity = $oldEntity;
            if(is_null($oldEntity)){
                $currentEntity = $entity;
                $em->persist($entity);
                $em->flush();
            }elseif( $oldEntity->getName() == $entity->getName()
                && $oldEntity->getDescription() == $entity->getDescription()
                && $oldEntity->getPrice() == $entity->getPrice()
            ){
                echo $oldEntity->getId()." - ".$oldEntity->getCreated()->format('Y-m-d H:i:s')." - ".$oldEntity->getSourceURL()." - ".$entity->getSourceURL()."\n";
                $this->fillPublish($oldEntity, $entity);
                return self::SAVE_ENTITY_BREAK;
            }else{
                //TODO: full merge, update time
                $oldEntity->setName($entity->getName());
                $oldEntity->setDescription($entity->getDescription());
                $oldEntity->setUpdated(new \DateTime());
                $oldEntity->setContentChanged(new \DateTime());
                $em->flush();
            }

            if(!is_null($oldEntity)){
                $this->fillPublish($oldEntity, $entity);
            }

            foreach($currentEntity->getPhotos() as $photo){
                /** @var $imageService ImageService */
                $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
                /** @var $photo AdvertisementPhoto */
                if($imageService->isFilled($photo)) continue;
                $imageService->saveFileByURL($photo->getUrl(), $photo);
            }

        }catch(\Exception $e){
            echo 'ERROR: '.$entity->getSourceURL().":".$e->getMessage()."<br/>\n";
            return self::SAVE_ENTITY_FAIL;
        }
        return self::SAVE_ENTITY_SUCCESS;
    }

    function fillLastAdvertisements($url, $type)
    {
        $domCrawler = $this->getPageCrawler($url);
        /** @var $parser KvartiraZhitomirUaParser */
        $parser = $this->container->get('housefinder.parser.parser.kvartira_zhitomir_ua');
        $entities = $parser->getEntities($domCrawler, $type);
        $successCnt = 0;
        $breakCnt = 0;
        $failCnt = 0;
        foreach ($entities as $entity){
            $res = $this->saveAdvertisementEntity($entity);
            if($res == self::SAVE_ENTITY_BREAK) $breakCnt++;
            if($res == self::SAVE_ENTITY_SUCCESS) $successCnt++;
            if($res == self::SAVE_ENTITY_FAIL) $failCnt++;
            //if($breakCnt >= 3) break;
        }
        return array(
            'success' => $successCnt,
            'break' => $breakCnt,
            'fail' => $failCnt,
        );
    }

    /**
     * @param Advertisement $advertisement
     * @param Advertisement $source
     * @return bool
     */
    private function fillPublish(Advertisement $advertisement, Advertisement $source)
    {
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');

        $publish = $advertisementService->findPublish($advertisement, $source->getCreated());
        if(is_null($publish)){
            $advertisementService->createPublish($advertisement, $source, $source->getCreated());
        }
        if(is_null($source->getUpdated())) return true;
        $publish = $advertisementService->findPublish($advertisement, $source->getUpdated());
        if(is_null($publish)){
            $advertisementService->createPublish($advertisement, $source, $source->getUpdated());
        }
    }
}
