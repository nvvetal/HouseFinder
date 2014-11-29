<?php
namespace HouseFinder\APIBundle\Entity\ApiResponse;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use FOS\RestBundle\Util\Codes;

abstract class ApiSuccessResponse extends ApiResponse
{
    /**
     * @Type("string")
     */
    public $code = Codes::HTTP_OK;

    /**
     * @Type("string")
     */
    public $message = 'ok';

    abstract public function getData();
    abstract public function setData($data);

}