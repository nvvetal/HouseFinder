<?php

namespace HouseFinder\APIBundle\Controller;


use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use HouseFinder\APIBundle\Exception\City\ApiCitiesNotFoundException;
use HouseFinder\APIBundle\Exception\City\ApiCityNotFoundException;
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

class AddressController extends FOSRestController
{
    /**
     * @Get("/city/name/{name}")
     * @ApiDoc(
     *  description="",
     *  section="Address",
     *  statusCodes={
     *      200="Successful",
     *      400="Invalid json message received",
     *      404={
     *          "City not found"
     *      },
     *      417="Data passed is not correct",
     *      422="SQL Error",
     *      500="The API token authentication expired"
     *  },
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiCityResponse",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function getCityByNameAction($name)
    {
        $request = $this->getRequest();
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');

        try {
            $address = $addressService->getAddressByCityName($name);
            if(is_null($address)) throw new ApiCityNotFoundException;
            return $this->view($responseService->getCity($address), Codes::HTTP_OK);
        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
        }
    }

    /**
     * @Get("/city/near/lat/{lat}/long/{long}")
     * @ApiDoc(
     *  description="",
     *  section="Address",
     *  statusCodes={
     *      200="Successful",
     *      400="Invalid json message received",
     *      404={
     *          "City not found"
     *      },
     *      417="Data passed is not correct",
     *      422="SQL Error",
     *      500="The API token authentication expired"
     *  },
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiCityResponse",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function getCityNear($lat, $long)
    {
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');
        $request = $this->getRequest();
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        try {
            $address = $addressService->getAddressCityNearCoords($lat, $long);
            if(is_null($address)) throw new ApiCityNotFoundException;
            return $this->view($responseService->getCity($address), Codes::HTTP_OK);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
        }
    }

    /**
     * @Get("/cities")
     * @ApiDoc(
     *  description="",
     *  section="Address",
     *  statusCodes={
     *      200="Successful",
     *      400="Invalid json message received",
     *      404={
     *          "Cities not found"
     *      },
     *      417="Data passed is not correct",
     *      422="SQL Error",
     *      500="The API token authentication expired"
     *  },
     *  output={
     *    "class"   = "HouseFinder\APIBundle\Entity\ApiResponse\ApiCitiesResponse",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function cgetCities()
    {
        $request = $this->getRequest();
        /** @var ResponseService $responseService */
        $responseService = $this->container->get('housefinder.api.service.response');
        /** @var AddressService $addressService */
        $addressService = $this->container->get('housefinder.service.address');
        try {
            $addresses = $addressService->getCities();
            if(count($addresses) == 0) throw new ApiCitiesNotFoundException;
            return $this->view($responseService->getCities($addresses), Codes::HTTP_OK);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view($responseService->getErrorResponse($e), $e->getCode());
        }
    }
}
