<?php

namespace HouseFinder\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="frontend_root")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/{path}", requirements={"path" = ".+"})
     * @Template()
     */
    public function forwardAction($path)
    {
        return $this->forward('HouseFinderFrontendBundle:Default:index');
    }
}
