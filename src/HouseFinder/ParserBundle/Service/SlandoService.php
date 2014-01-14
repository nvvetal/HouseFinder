<?php

namespace HouseFinder\ParserBundle\Service;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementExternal;
use HouseFinder\CoreBundle\Entity\AdvertisementPhoto;
use HouseFinder\StorageBundle\Service\ImageService;

class SlandoService extends BaseService
{
    protected $options = array();

    /**
     * @param AdvertisementExternal $entity
     * @return mixed
     */
    function saveAdvertisementEntity(AdvertisementExternal &$entity)
    {
        /** @var $em EntityManager */
        $em = $this->container->get('Doctrine')->getManager();
        try {
            /** @var $oldEntity AdvertisementSlando */
            $oldEntity = $em->getRepository('HouseFinderCoreBundle:AdvertisementSlando')
                ->findOneBy(array('sourceHash' => $entity->getSourceHash()));
            $currentEntity = $oldEntity;
            if(is_null($oldEntity)){
                $currentEntity = $entity;
                $em->persist($entity);
                $em->flush();
            }elseif( $oldEntity->getName() == $entity->getName()
                && $oldEntity->getDescription() == $entity->getDescription()
            ){
                return self::SAVE_ENTITY_BREAK;
            }else{
                //TODO: full merge, update time
                $oldEntity->setName($entity->getName());
                $oldEntity->setDescription($entity->getDescription());
                $oldEntity->setUpdated(new \DateTime());
                $oldEntity->setContentChanged(new \DateTime());
                $em->flush();
            }
            foreach($currentEntity->getPhotos() as $photo){
                /**
                 * @var $imageService ImageService
                 */
                $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
                /**
                 * @var $photo AdvertisementPhoto
                 */
                if($imageService->isFilled($photo)) continue;
                $imageService->saveFileByURL($photo->getUrl(), $photo);
            }

        }catch(\Exception $e){
            echo 'ERROR: '.$entity->getSourceURL().":".$e->getMessage()."<br/>\n";
            return self::SAVE_ENTITY_FAIL;
        }
        return self::SAVE_ENTITY_SUCCESS;
    }
}
