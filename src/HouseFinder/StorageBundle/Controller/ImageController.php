<?php

namespace HouseFinder\StorageBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use HouseFinder\StorageBundle\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    public function advertisementPhotoAction($id)
    {
        /**
         * @var $imageService ImageService
         */
        $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
        /**
         * @var $em EntityManager
         */
        $em = $this->container->get('Doctrine')->getManager();
        /**
         * @var $entity AdvertisementPhoto
         */
        $entity = $em->getRepository('HouseFinderCoreBundle:AdvertisementPhoto')->find($id);
        if(is_null($entity)) throw new NotFoundHttpException('ID '.$id.'not found!');
        $data = $imageService->getFileData($entity);
        $path = explode('/', $data['path']);
        $url = $this->get('router')->generate('storage_image_url', array(
            'id'        => $id,
            'path1'     => $path[0],
            'path2'     => $path[1],
            'context'   => $data['context'],
            'ext'       => $data['ext'],
        ));
        return new JsonResponse(array('url' => $url));
    }
}
