<?php
namespace HouseFinder\APIBundle\Entity\ApiResponse;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use FOS\RestBundle\Util\Codes;

class ApiAdvertisementFullResponse extends ApiSuccessResponse
{
    /**
     * @Type("HouseFinder\APIBundle\Entity\Response\AdvertisementFullResponse")
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