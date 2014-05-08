<?php

namespace HouseFinder\APIBundle\Controller;


use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementRepository;
use HouseFinder\CoreBundle\Entity\DataContainer;
use HouseFinder\CoreBundle\Entity\http;
use HouseFinder\StorageBundle\Service\ImageService;
use MyProject\Proxies\__CG__\stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Prefix,
    FOS\RestBundle\Controller\Annotations\NamePrefix,
    FOS\RestBundle\Controller\Annotations\RouteResource,
    FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use HouseFinder\APIBundle\Form\Advertisement\AdvertisementListType;

class AdvertisementController extends FOSRestController
{
    /**
     * @Get("/list")
     * @ApiDoc(
     *  description="",
     *  section="Advertisement",
     *  input="HouseFinder\APIBundle\Form\Advertisement\AdvertisementListType"
     * )
     */
    public function cgetAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $class = new DataContainer();
            $form = $this->createForm(new AdvertisementListType(), $class);
            $form->bind($request);
            if ($form->isValid() == false) {
                foreach ($form->getErrors() as $key => $error) {
                    throw new \Exception('FORM::'.$key.'::'.$error->getMessage(), HTTP::HTTP_NOT_ACCEPTABLE);
                }
            }
            /** @var AdvertisementRepository $advertisementsRepo */
            $advertisementsRepo = $em->getRepository('HouseFinderCoreBundle:Advertisement');
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
                    'address' => '',
                );
            }
            return $this->view(array(
                'code'      => HTTP::HTTP_SUCCESS,
                'message'   => 'ok',
                'data'      => $data,
            ), HTTP::HTTP_SUCCESS);

        }catch(\Exception $e){
            $this->get('housefinder.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_list');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
            ), $e->getCode());
        }
    }

}
