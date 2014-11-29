<?php
namespace HouseFinder\APIBundle\Exception\City;

use Exception;
use FOS\RestBundle\Util\Codes;
use HouseFinder\APIBundle\Exception\ApiException;

class ApiCityNotFoundException extends ApiException{

    public function __construct($message = '', $code = Codes::HTTP_NOT_FOUND, Exception $previous = null) {
        $this->setDomain('validators');
        parent::__construct('city.not.found', $code, $previous);
    }

}
