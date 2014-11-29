<?php

namespace HouseFinder\APIBundle\Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use HouseFinder\APIBundle\Service\ResponseService;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\AdvertisementRepository;
use HouseFinder\CoreBundle\Entity\DataContainer;
use HouseFinder\APIBundle\Entity\Output;
use HouseFinder\CoreBundle\Entity\http;
use HouseFinder\CoreBundle\Service\AddressService;
use HouseFinder\CoreBundle\Service\AdvertisementService;
use HouseFinder\CoreBundle\Service\UserService;
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
use HouseFinder\APIBundle\Form\Advertisement\AdvertisementMapType;
use FOS\RestBundle\Util\Codes;

class AdvertisementController extends FOSRestController
{
    /**
     * @Get("/list")
     * @ApiDoc(
     *  description="",
     *  section="Advertisement",
     *  input="HouseFinder\APIBundle\Form\Advertisement\AdvertisementListType",
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiAdvertisementsListResponse",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function getListAction()
    {
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');
        try {
            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $class = new DataContainer();
            $form = $this->createForm(new AdvertisementListType(), $class);
            $form->bind($request);
            if ($form->isValid() == false) {
                foreach ($form->getErrors() as $key => $error) {
                    throw new \Exception('FORM::'.$key.'::'.$error->getMessage(), Codes::HTTP_NOT_ACCEPTABLE);
                }
            }
            $pager = $advertisementService->searchAdvertisements($class);
            return $this->view($responseService->getAdvertisementsList($pager), Codes::HTTP_OK);
        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_list');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
        }
    }

    /**
     * @Get("/map")
     * @ApiDoc(
     *  description="",
     *  section="Advertisement",
     *  input="HouseFinder\APIBundle\Form\Advertisement\AdvertisementMapType",
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiAdvertisementsResponse",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function cgetMapAction(Request $request)
    {
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');
        try {
            $em = $this->getDoctrine()->getManager();
            $class = new DataContainer();
            $form = $this->createForm(new AdvertisementMapType(), $class);
            $form->bind($request);
            if ($form->isValid() == false) {
                foreach ($form->getErrors() as $key => $error) {
                    throw new \Exception('FORM::'.$key.'::'.$error->getMessage(), Codes::HTTP_NOT_ACCEPTABLE);
                }
            }
            $data = $advertisementService->getAdvertisementsForMap($class);
            return $this->view($responseService->getAdvertisements($data), Codes::HTTP_OK);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_map_list');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
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
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiAdvertisementFullResponse",
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
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');
        try {
            return $this->view($responseService->getAdvertisementFull($advertisement), Codes::HTTP_OK);
        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_advertisement_item');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
        }
    }



}
