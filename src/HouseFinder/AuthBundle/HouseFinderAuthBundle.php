<?php

namespace HouseFinder\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HouseFinderAuthBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
