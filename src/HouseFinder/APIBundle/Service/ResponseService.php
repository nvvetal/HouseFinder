<?php

namespace HouseFinder\APIBundle\Service;

use HouseFinder\APIBundle\Entity\ApiResponse\ApiAddressResponse;
use HouseFinder\APIBundle\Entity\ApiResponse\ApiAdvertisementFullResponse;
use HouseFinder\APIBundle\Entity\ApiResponse\ApiAdvertisementsResponse;
use HouseFinder\APIBundle\Entity\ApiResponse\ApiCitiesResponse;
use HouseFinder\APIBundle\Entity\ApiResponse\ApiCityResponse;
use HouseFinder\APIBundle\Entity\ApiResponse\ApiErrorResponse;
use HouseFinder\APIBundle\Entity\Response\AddressResponse;
use HouseFinder\APIBundle\Entity\Response\AdvertisementFullResponse;
use HouseFinder\APIBundle\Entity\Response\AdvertisementOwnerResponse;
use HouseFinder\APIBundle\Entity\Response\AdvertisementResponse;
use HouseFinder\APIBundle\Entity\Response\AdvertisementsPagerResponse;
use HouseFinder\APIBundle\Entity\Response\CityResponse;
use HouseFinder\APIBundle\Entity\Response\HouseResponse;
use HouseFinder\CoreBundle\Entity\Address;
use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\CoreBundle\Entity\House;
use HouseFinder\CoreBundle\Entity\Pager\AdvertisementPager;
use HouseFinder\CoreBundle\Entity\User;
use HouseFinder\CoreBundle\Service\AdvertisementService;
use Symfony\Component\Translation\Translator;

class ResponseService
{
    protected $container;
    /** @var Translator $translator */
    protected $translator;

    public function __construct($container)
    {
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    /**
     * @param $e
     * @return array
     */
    public function getErrorResponse($e)
    {
        $domain = null;
        $lang = null;
        $parameters = array();
        if(method_exists($e, 'getDomain')) {
            $domain = $e->getDomain();
        }
        if(method_exists($e, 'getLang')) {
            $lang = $e->getLang();
        }
        if(is_null($lang)){
            $this->translator->setLocale($lang);
        }
        if(method_exists($e, 'getParameters')) {
            $params = $e->getParameters();
            foreach($params as $k => $v) {
                $parameters[$k] = $this->container->getParameter($v);
            }
        }
        if(method_exists($e, 'getParams')) {
            $parameters = array_merge($e->getParams(), $parameters);

        }
        if (method_exists($e, 'getCallbackParams')) {
            $parameters = array_merge($e->getCallbackParams($this->container), $parameters);
        }
        $msg = $this->translator->trans($e->getMessage(), $parameters, $domain);
        $apiResponse = new ApiErrorResponse();
        $apiResponse->setCode($e->getCode());
        $apiResponse->setMessage($msg);
        return $apiResponse->getArray();
    }

    /**
     * @param Address $address
     * @return array
     */
    public function getCity(Address $address)
    {
        $response = $this->fillCity($address);
        $apiResponse = new ApiCityResponse();
        $apiResponse->setData($response);
        return $apiResponse->getArray();
    }

    /**
     * @param Address $address
     * @return CityResponse
     */
    private function fillCity(Address $address)
    {
        $response = new CityResponse();
        $response->setId($address->getId());
        $response->setLocality($address->getLocality());
        $response->setRegion($address->getRegion());
        $response->setLatitude($address->getLatitude());
        $response->setLongitude($address->getLongitude());
        return $response;
    }

    /**
     * @param Address $address
     * @return array
     */
    public function getAddress(Address $address)
    {
        $response = $this->fillAddress($address);
        $apiResponse = new ApiAddressResponse();
        $apiResponse->setData($response);
        return $apiResponse->getArray();
    }

    /**
     * @param Address $address
     * @return AddressResponse
     */
    private function fillAddress(Address $address)
    {
        $response = new AddressResponse();
        $response->setId($address->getId());
        $response->setLocality($address->getLocality());
        $response->setRegion($address->getRegion());
        $response->setStreet($address->getStreet());
        $response->setStreetNumber($address->getStreetNumber());
        $response->setLatitude($address->getLatitude());
        $response->setLongitude($address->getLongitude());
        $response->setLine($this->fillAddressLine($address));
        return $response;
    }

    /**
     * @param Address $address
     * @return string
     */
    private function fillAddressLine(Address $address)
    {
        $line = array();
        $line[] = $address->getLocality();
        $street = $address->getStreet();
        $number = $address->getStreetNumber();
        if(!empty($street)){
            $line[] = $street;
            if(!empty($number)) $line[] = $number;
        }
        return implode(', ', $line);
    }

    /**
     * @param array $addresses
     * @return array
     */
    public function getCities(array $addresses)
    {
        $apiResponse = new ApiCitiesResponse();
        $data = array();
        /** @var Address $address */
        foreach($addresses as $address){
            $data[] = $this->fillCity($address);
        }
        $apiResponse->setData($data);
        return $apiResponse->getArray();
    }

    /**
     * @param AdvertisementPager $pager
     * @return array
     */
    public function getAdvertisementsList(AdvertisementPager $pager)
    {
        $advertisementsResponse = new ApiAdvertisementsResponse();
        $response = new AdvertisementsPagerResponse();
        $response->fillFromPager($pager);
        if(count($pager->getItems()) > 0){
            foreach($pager->getItems() as $advertisement){
                $response->addItem($this->fillAdvertisement($advertisement));
            }
        }
        $advertisementsResponse->setData($response);
        return $advertisementsResponse->getArray();
    }

    /**
     * @param Advertisement $advertisement
     * @return AdvertisementResponse
     */
    private function fillAdvertisement(Advertisement $advertisement)
    {
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');
        $photoUrl = $advertisementService->getFirstPhotoUrl($advertisement);
        $response = new AdvertisementResponse();
        $response->setId($advertisement->getId());
        $response->setUserId($advertisement->getUser()->getId());
        $response->setName($advertisement->getName());
        $response->setDescription(mb_substr(iconv('UTF-8', 'UTF-8//IGNORE', $advertisement->getDescription()),0,170,'UTF-8').'...');
        $response->setPrice($advertisement->getPrice());
        $response->setCurrency($advertisement->getCurrency());
        $response->setPhoto($photoUrl);
        $response->setLastDate($advertisement->getLastUpdated());
        $response->setAddress($this->fillAddress($advertisement->getAddress()));
        return $response;
    }

    /**
     * @param Advertisement $advertisement
     * @return AdvertisementFullResponse
     */
    private function fillAdvertisementFull(Advertisement $advertisement)
    {
        $response = new AdvertisementFullResponse();
        /** @var AdvertisementService $advertisementService */
        $advertisementService = $this->container->get('housefinder.service.advertisement');
        $photoUrl = $advertisementService->getFirstPhotoUrl($advertisement);
        $response->setId($advertisement->getId());
        $response->setOwner($this->fillAdvertisementOwner($advertisement->getUser()));
        $response->setName($advertisement->getName());
        $response->setDescription($advertisement->getDescription());
        $response->setPrice($advertisement->getPrice());
        $response->setCurrency($advertisement->getCurrency());
        $response->setPhoto($photoUrl);
        $response->setLastDate($advertisement->getLastUpdated());
        $response->setAddress($this->fillAddress($advertisement->getAddress()));
        if(!is_null($advertisement->getHouse()))$response->setHouse($this->fillHouse($advertisement->getHouse()));
        $response->setType($advertisement->getType());
        $response->setRentType($advertisement->getRentType());
        $response->setRentStartDate($advertisement->getRentStartDate());
        $response->setHouseType($advertisement->getHouseType());
        $response->setFullSpace($advertisement->getFullSpace());
        $response->setLivingSpace($advertisement->getLivingSpace());
        $response->setLevel($advertisement->getLevel());
        $response->setMaxLevels($advertisement->getMaxLevels());
        $response->setWallType($advertisement->getWallType());
        $response->setBrickType($advertisement->getBrickType());
        $response->setHeatingType($advertisement->getHeatingType());
        $response->setSpecial($advertisement->getSpecial());
        if(count($advertisementService->getPhotoURLs($advertisement)) > 0)$response->setPhotos($advertisementService->getPhotoURLs($advertisement));
        if(count($advertisementService->getRooms($advertisement)) > 0)$response->setRooms($advertisementService->getRooms($advertisement));
        $response->setRoomsLiving(count($advertisement->getLivingRooms()));
        $response->setCreated($advertisement->getCreated());

        return $response;
    }

    /**
     * @param User $user
     * @return AdvertisementOwnerResponse
     */
    private function fillAdvertisementOwner(User $user)
    {
        $response = new AdvertisementOwnerResponse();
        $response->setId($user->getId());
        $response->setUsername($user->getUsernameFiltered());
        return $response;
    }

    /**
     * @param House $house
     * @return HouseResponse
     */
    private function fillHouse(House $house)
    {
        $response = new HouseResponse();
        $response->setId($house->getId());
        $response->setAddress($this->fillAddress($house->getAddress()));
        $response->setBrickType($house->getBrickType());
        $response->setWallType($house->getWallType());
        return $response;
    }


    /**
     * @param $advertisements
     * @return array
     */
    public function getAdvertisements($advertisements)
    {
        $advertisementsResponse = new ApiAdvertisementsResponse();
        $data = array();
        foreach ($advertisements as $advertisement){
            $data[] = $this->fillAdvertisement($advertisement);
        }
        $advertisementsResponse->setData($data);
        return $advertisementsResponse->getArray();
    }

    /**
     * @param Advertisement $advertisement
     * @return array
     */
    public function getAdvertisementFull(Advertisement $advertisement)
    {
        $response = $this->fillAdvertisementFull($advertisement);
        $apiResponse = new ApiAdvertisementFullResponse();
        $apiResponse->setData($response);
        return $apiResponse->getArray();
    }
}