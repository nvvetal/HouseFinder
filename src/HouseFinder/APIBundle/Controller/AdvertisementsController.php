<?php

namespace HouseFinder\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdvertisementsController extends Controller
{
    public function listAction()
    {
        return new JsonResponse(array(array('hoho'=>$this->getRequest()->get('hoho'))));
    }
}
