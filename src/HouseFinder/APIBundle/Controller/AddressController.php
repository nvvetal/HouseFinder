<?php

namespace HouseFinder\APIBundle\Controller;


use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
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
     *    "class"   = "HouseFinder\APIBundle\Entity\Output",
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
        try {
            /** @var AddressService $addressService */
            $addressService = $this->container->get('housefinder.service.address');
            $address = $addressService->getAddressByCityNameREST($name);
            if(is_null($address)) throw new \Exception('City not found', 404);
            return $this->view(array(
                'code'      => HTTP::HTTP_SUCCESS,
                'message'   => 'ok',
                'data'      => $address,
            ), HTTP::HTTP_SUCCESS);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => array(),
            ), $e->getCode());
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
     *    "class"   = "HouseFinder\APIBundle\Entity\Output",
     *    "parsers" = {
     *      "Nelmio\ApiDocBundle\Parser\JmsMetadataParser",
     *      "Nelmio\ApiDocBundle\Parser\ValidationParser"
     *    }
     *  }
     * )
     */
    public function getCityNear($lat, $long)
    {
        $request = $this->getRequest();
        try {
            /** @var AddressService $addressService */
            $addressService = $this->container->get('housefinder.service.address');
            $address = $addressService->getAddressCityNearCoordsREST($lat, $long);
            if(is_null($address)) throw new \Exception('City not found', 404);
            return $this->view(array(
                'code'      => HTTP::HTTP_SUCCESS,
                'message'   => 'ok',
                'data'      => $address,
            ), HTTP::HTTP_SUCCESS);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => array(),
            ), $e->getCode());
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
     *    "class"   = "HouseFinder\APIBundle\Entity\Output",
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
        try {
            /** @var AddressService $addressService */
            $addressService = $this->container->get('housefinder.service.address');
            $addresses = $addressService->getAddressCitiesREST();
            if(count($addresses) == 0) throw new \Exception('Cities not found', 404);
            return $this->view(array(
                'code'      => HTTP::HTTP_SUCCESS,
                'message'   => 'ok',
                'data'      => $addresses,
            ), HTTP::HTTP_SUCCESS);

        }catch(\Exception $e){
            $this->get('housefinder.service.logger')->write('[res error][error '.$e->getMessage().'][code '.$e->getCode().'][data '.print_r($request->getContent(),true).']', 'api_address_city_by_name');
            return $this->view(array(
                'code'      => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => array(),
            ), $e->getCode());
        }
    }
}
