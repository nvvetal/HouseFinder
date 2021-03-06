<?php

namespace HouseFinder\ParserBundle\Parser;

use HouseFinder\CoreBundle\Entity\IssueKomunal;
use HouseFinder\CoreBundle\Service\HouseService;
use HouseFinder\CoreBundle\Service\OrganizationService;
use HouseFinder\ParserBundle\Service\AddressService;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Parses data from komunal.com.ua
 * Class KomunalParser
 * @package HouseFinder\ParserBundle\Parser
 */
class KomunalParser
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function fetchAddresses(Crawler $data)
    {
        $addresses = array();
        try {
            $title = $data->filter('a')->eq(0)->attr('title');
            $title = str_replace('<br />', '<br/>', $title);
            $title = explode('<br/>', $title);
            for($i = 1; $i < count($title); $i++){
                $addresses[] = 'Украина, Житомир, '.trim($title[$i]);
            }
        }catch(\Exception $e){
            $addresses[] = 'Украина, Житомир, '.trim($data->text());
        }
        $addr = array();
        foreach($addresses as $address){
            $parts = $this->parseAddressParts($address);
            if(count($parts) > 0) {
                foreach($parts as $part){
                    $addr[] = $part;
                }
            }else{
                $addr[] = $address;
            }
        }

        return $addr;
    }

    private function parseAddressParts($address)
    {
        $parts = array();
        $address = str_replace(' -', '-', $address);
        $address = str_replace(' ,', ',', $address);
        $begin = mb_strpos($address, 'буд.')+7;
        $number = trim(substr($address, $begin));
        $numbers = explode(',', $number);
        if(count($numbers) == 1) return $parts;
        foreach($numbers as $numberData){
            $n = explode('-', $numberData);
            if(count($n) == 1) {
                $parts[] = substr($address, 0, $begin).$n[0];
                continue;
            }
            for($i = $n[0]; $i <= $n[count($n)-1]; $i++) $parts[] = substr($address, 0, $begin).$i;
        }
        return $parts;
    }


    public function fetchAllIssues($content)
    {
        $crawler = new Crawler($content);
        $self = $this;
        $data = $crawler->filter('table#archive_table')->each(function (Crawler $node, $i) use ($self) {
            $title = trim($node->filter('th')->eq(0)->text());
            if(!preg_match("/проблеми\: (\d+-\d+-\d+)   \((\d+\-\d+\-\d+ \d+\:\d+)\)/i", $title, $m)) return false;
            $data['documentNumber'] = $m[1];
            //if($data['documentNumber'] != '58-14-1229') return false;
            //if($data['documentNumber'] != '17-14-4347') return false;
            $data['created'] = \DateTime::createFromFormat('d-m-Y H:i', $m[2]);
            $fullData = $node->filter('table.noborder_table')->eq(0);
            $data['addresses']  = $self->fetchAddresses($fullData->filter('td')->eq(1));
            $data['organization'] = trim($fullData->filter('td')->eq(3)->text());
            $data['type'] = trim($fullData->filter('td')->eq(5)->text());
            $data['description'] = trim($fullData->filter('td')->eq(7)->text());
            $data['priority'] = trim($fullData->filter('td')->eq(9)->text());
            return $data;
        });
        $entities = array();
        if(count($data) == 0) return $entities;
        foreach($data as $item){
            if($item === false) continue;
            foreach($item['addresses'] as $addr) {
                $entity = new IssueKomunal();
                $entity->setDocumentNumber($item['documentNumber']);
                $entity->setCreated($item['created']);
                /** @var AddressService $addressService */
                $addressService = $this->container->get('housefinder.parser.service.address');
                $address = $addressService->getAddress($addr);
                /** @var HouseService $houseService */
                $houseService = $this->container->get('housefinder.service.house');
                /** @var OrganizationService $organizationService */
                $organizationService = $this->container->get('housefinder.service.organization');
                $entity->setHouse($houseService->getHouseByAddress($address));
                $addressOrganization = $addressService->getAddress('Украина, Житомир');
                $organization = $organizationService->getOrganization($addressOrganization, $item['organization']);
                if (is_null($organization)) $organization = $organizationService->createOrganization($addressOrganization, $item['organization'], array());
                $entity->setOrganization($organization);
                $entity->setTypeDescription($item['type']);
                $entity->setType($this->getType($item['type']));
                $entity->setDescription($item['description']);
                $entity->setPriority($this->getPriority($item['priority']));
                $entities[] = $entity;
            }
        }
        return $entities;
    }

    private function getPriority($text)
    {
        switch($text)
        {
            case "Середній":
                return IssueKomunal::PRIORITY_NORMAL;
            case "Високий":
                return IssueKomunal::PRIORITY_HIGH;
            default:
                return IssueKomunal::PRIORITY_LOW;
        }
    }

    private function getType($text){
        $type = IssueKomunal::TYPE_OTHER;
        switch($text){
            case "Освітлення вуличне":
                $type = IssueKomunal::TYPE_LIGHT;
                break;
            case "Вода холодна відсутня":
            case "Вода, витоки із землі":
            case "Водорозбірна колонка":
            case "Вода, витоки із колодязя":
                $type = IssueKomunal::TYPE_WATER;
                break;
            case "Газ виток":
                $type = IssueKomunal::TYPE_GAS;
                break;
            case "технічний стан будинку":
                $type = IssueKomunal::TYPE_TECH;
                break;
            case "Каналізація, колодязі, відкриті":
            case "каналізаційні колодязі":
            case "Каналізація зовнішня забита":
            case "засмідчення каналізаційних мереж":
                $type = IssueKomunal::TYPE_CANALIZATION;
                break;
        }
        return $type;
    }
}

