<?php
namespace HouseFinder\APIBundle\Entity\Response;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

class Response
{
    public function getArray()
    {
        return (array) $this;
    }

}
