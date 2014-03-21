<?php

namespace HouseFinder\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
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
        $advertisements = $em->getRepository('HouseFinderCoreBundle:Advertisement')->findAll();
        $data = array();
        foreach($advertisements as $advertisement){
            /**
             * @var $advertisement Advertisement
             */
            $data[] = array(
                'id'        => $advertisement->getId(),
                'userId'    => $advertisement->getUser()->getId(),
            );
        }
        return $data;
    }
}
