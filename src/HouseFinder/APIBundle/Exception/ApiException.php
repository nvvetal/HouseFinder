<?php
namespace HouseFinder\APIBundle\Exception;

use Exception;

/**
 * Created by PhpStorm.
 * User: ag
 * Date: 16.10.14
 * Time: 11:58
 */

class ApiException extends Exception {

    /**
     * @var string|null $domain
     */
    protected $domain = null;

    protected $lang = null;

    protected $parameters = array();
    protected $params = array();

    /**
     * @return null|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param null|string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return null
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param null $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $params
     */
    public function setParameters($params)
    {
        $this->parameters = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

}