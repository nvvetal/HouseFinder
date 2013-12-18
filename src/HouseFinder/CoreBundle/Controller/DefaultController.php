<?php

namespace HouseFinder\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/secured/{name}")
     * @Template()
     */
    public function indexAction($name = 'anonymous')
    {
        //var_dump($this->getUser());
        $service = $this->container->get('housefinder.parser.service.slando');
        $service->fillLastAdvertisements('http://zhitomir.zht.slando.ua/nedvizhimost/arenda-kvartir/');
        return array('name' => $name);
    }
}
