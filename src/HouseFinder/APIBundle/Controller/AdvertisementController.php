<?php

namespace HouseFinder\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\StorageBundle\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Prefix,
    FOS\RestBundle\Controller\Annotations\NamePrefix,
    FOS\RestBundle\Controller\Annotations\RouteResource,
    FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * @NamePrefix("api_")
 * Following annotation is redundant, since FosRestController implements ClassResourceInterface
 * so the Controller name is used to define the resource. However with this annotation its
 * possible to set the resource to something else unrelated to the Controller name
 * @RouteResource("Advertisement")
 */
class AdvertisementController extends FOSRestController
{

    /**
     * Collection get action
     * @var Request $request
     * @return array
     *
     * @View()
     */
    public function cgetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $params = array(
            'perPage' => 30,
            'page'  => 0,
        );
        $advertisements = $em->getRepository('HouseFinderCoreBundle:Advertisement')->search($params);
        $data = array(
            'pages' => $advertisements['pages'],
        );
        foreach($advertisements['items'] as $advertisement){
            $photoUrl = '';
            /**
             * @var $advertisement Advertisement
             */
            $photos = $advertisement->getPhotos();
            if(!is_null($photos) && count($photos) > 0) {
                /**
                 * @var $imageService ImageService
                 */
                $imageService = $this->container->get('housefinder.storage.service.image.advertisement.photo');
                $photoUrl = $imageService->getURL($photos[0]);
            }
            $data['items'][] = array(
                'id'        => $advertisement->getId(),
                'userId'    => $advertisement->getUser()->getId(),
                'name'      => $advertisement->getName(),
                'price'     => $advertisement->getPrice(),
                'currency'  => $advertisement->getCurrency(),
                'photo'     => $photoUrl,
                'lastDate'  => $advertisement->getLastUpdated()->format('Y-m-d H:i'),
            );
        }
        return $data;
    }
}
