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

        return array('name' => $name);
    }
}
