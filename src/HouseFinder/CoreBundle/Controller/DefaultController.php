<?php

namespace HouseFinder\CoreBundle\Controller;

use Geocoder\HttpAdapter\CurlHttpAdapter;
use Ivory\GoogleMap\Services\Geocoding\Geocoder;
use Ivory\GoogleMap\Services\Geocoding\GeocoderProvider;
use Ivory\GoogleMap\Services\Geocoding\GeocoderRequest;
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
//        $geocoder = new Geocoder();
//        $provider = new GeocoderProvider(new CurlHttpAdapter());
//        $geocoder->registerProvider($provider);
//        $request = new GeocoderRequest();
//        $request->setLanguage("ru");
//        $request->setAddress("Жукова 7, житомир");
//        $ret = $geocoder->geocode($request);
//        var_dump($ret);
//        exit;

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
