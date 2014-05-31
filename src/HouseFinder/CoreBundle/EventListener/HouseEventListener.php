<?php

namespace HouseFinder\CoreBundle\EventListener;
use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Event\FilterAddressCreateEvent;
use HouseFinder\CoreBundle\Service\HouseService;
use HouseFinder\CoreBundle\Service\LoggerService;


class HouseEventListener
{
    protected $logger;
    protected $houseService;

    public function __construct(HouseService $houseService, LoggerService $logger)
    {
        $this->logger = $logger;
        $this->houseService = $houseService;
    }

    public function onAddressCreateEvent(FilterAddressCreateEvent $event)
    {
        try {
            $address = $event->getAddress();
            if(is_null($address->getStreetNumber())) throw new \Exception('Street number not filled');
            $house = $this->houseService->getHouseByAddress($address);
            if(!is_null($house)) throw new \Exception('House already exists');
            $this->houseService->createFromAddress($address);
        } catch(\Exception $e) {
            $this->logger->write('[error '.$e->getMessage().']', 'error_event_house_by_address_create');
            return false;
        }
        return true;
    }


}