<?php

namespace HouseFinder\ParserBundle\Service;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementExternal;

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
            if(is_null($oldEntity)){
                $em->persist($entity);
                $em->flush();
            }elseif( $oldEntity->getName() == $entity->getName()
                && $oldEntity->getDescription() == $entity->getDescription()
            ){
                return self::SAVE_ENTITY_BREAK;
            }else{
                //TODO: full merge, updated time
                $oldEntity->setName($entity->getName());
                $oldEntity->setDescription($entity->getDescription());
                $oldEntity->setUpdated(new \DateTime());
                $oldEntity->setContentChanged(new \DateTime());
                //$em->merge($entity);
                $em->flush();
            }
        }catch(\Exception $e){
            echo 'ERROR: '.$entity->getSourceURL()."<br/>\n";
            return self::SAVE_ENTITY_FAIL;
        }
        return self::SAVE_ENTITY_SUCCESS;
    }
}
