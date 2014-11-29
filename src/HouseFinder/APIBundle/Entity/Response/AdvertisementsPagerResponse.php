<?php
namespace HouseFinder\APIBundle\Entity\Response;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class AdvertisementsPagerResponse extends PagerResponse
{
    /**
     * @Type("array<HouseFinder\APIBundle\Entity\Response\AdvertisementResponse>")
     */
    public $items;
}