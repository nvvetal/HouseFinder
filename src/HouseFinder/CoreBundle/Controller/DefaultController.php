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

    /**
     * @Route("/route/{fromName}/{toName}")
     * @Template()
     */
    public function routeAction($fromName, $toName)
    {
        /**
         * @var \Ivory\GoogleMap\Services\Directions\Directions $directions
         */
        $directions = $this->get('ivory_google_map.directions');
        /**
         * @var \Ivory\GoogleMap\Services\Directions\DirectionsRequest $request
         */
        $request = $this->get('ivory_google_map.directions_request');
        $request->setOrigin($fromName);
        $request->setDestination($toName);
        $request->setDepartureTime(new \DateTime());
        $request->setLanguage("ru");
        $response = $directions->route($request);
        $status = $response->getStatus();
        $routes = $response->getRoutes();
        return array('status' => $status, 'routes' => $routes);
    }
}
