<?php

namespace HouseFinder\CoreBundle\Entity\Pager;

abstract class EntityPager
{
    /** @var int $count */
    protected $count;
    /** @var int $pages */
    protected $pages;
    /** @var array $items */
    protected $items;

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param int $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    abstract public function addItem($item);
}