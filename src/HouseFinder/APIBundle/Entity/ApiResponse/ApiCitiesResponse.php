<?php
namespace HouseFinder\APIBundle\Entity\ApiResponse;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use FOS\RestBundle\Util\Codes;

class ApiCitiesResponse extends ApiSuccessResponse
{
    /**
     * @Type("array<HouseFinder\APIBundle\Entity\Response\CityResponse>")
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