<?php
namespace HouseFinder\APIBundle\Entity\ApiResponse;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use FOS\RestBundle\Util\Codes;

class ApiAddressResponse extends ApiSuccessResponse
{
    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\AddressResponse")
     */
    public $data;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}