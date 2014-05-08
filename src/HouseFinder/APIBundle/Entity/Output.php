<?php
namespace HouseFinder\APIBundle\Entity;
use JMS\Serializer\Annotation\Type;
class Output
{
    /**
     * HTTP Code
     * @var integer
     * @Type("integer")
     */
    protected $code;

    /**
     * Message (ok or error message)
     * @var string
     * @Type("string")
     */
    protected $message;

    /**
     * Data
     * @var array
     * @Type("array")
     */
    protected $data = array();

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}