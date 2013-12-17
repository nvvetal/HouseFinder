<?php

namespace HouseFinder\ParseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HouseFinderParseBundle:Default:index.html.twig', array('name' => $name));
    }
}
