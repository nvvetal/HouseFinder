<?php

namespace HouseFinder\CoreBundle\Entity\Pager;

use HouseFinder\CoreBundle\Entity\Advertisement;

class AdvertisementPager extends EntityPager
{
    /**
     * @param Advertisement $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }
}