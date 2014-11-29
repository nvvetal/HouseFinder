<?php
namespace HouseFinder\APIBundle\Entity\Response;
use HouseFinder\CoreBundle\Entity\Pager\EntityPager;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


abstract class PagerResponse extends Response
{
    /**
     * @Type("integer")
     */
    public $count;

    /**
     * @Type("integer")
     */
    public $pages;

    /**
     * @Type("array")
     */
    public $items;

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
     * @return mixed
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

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function addItem($item){
        $this->items[] = $item;
    }

    /**
     * @param EntityPager $pager
     */
    public function fillFromPager(EntityPager $pager)
    {
        $this->setCount($pager->getCount());
        $this->setPages($pager->getPages());
    }
}