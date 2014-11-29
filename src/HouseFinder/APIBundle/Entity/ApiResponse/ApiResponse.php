<?php
namespace HouseFinder\APIBundle\Entity\ApiResponse;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

class ApiResponse
{
    /**
     * @Type("string")
     */
    public $code;

    /**
     * @Type("string")
     */
    public $message;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getArray()
    {
        return (array) $this;
    }

}