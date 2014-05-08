<?php

namespace HouseFinder\APIBundle\Controller;


use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementRepository;
use HouseFinder\CoreBundle\Entity\DataContainer;
use HouseFinder\APIBundle\Entity\Output;
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
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_list');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => array(),
            ), $e->getCode());
        }
    }

    /**
     * @Get("/{id}")
     * @ApiDoc(
     *  description="",
     *  section="Advertisement",
     *  statusCodes={
     *      200="Successful",
     *      400="Invalid json message received",
     *      404={
     *          "Advertisement not found"
     *      },
     *      417="Data passed is not correct",
     *      422="SQL Error",
     *      500="The API token authentication expired"
     *  },
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\Output",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function getAdvertisementAction(Advertisement $advertisement)
    {
        $request = $this->getRequest();
        try {
            return $this->view(array(
                'code'      => HTTP::HTTP_SUCCESS,
                'message'   => 'ok',
                'data'      => array(
                    'id'            => $advertisement->getId(),
                    'name'          => $advertisement->getName(),
                    'description'   => trim($advertisement->getDescription()),
                    'ownerId'       => $advertisement->getUser()->getId(),
                ),
            ), HTTP::HTTP_SUCCESS);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_item');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => array(),
            ), $e->getCode());
        }
    }



}
