<?php

namespace HouseFinder\ParserBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\House;
use HouseFinder\CoreBundle\Entity\IssueKomunal;
use HouseFinder\CoreBundle\Entity\Organization;
use HouseFinder\ParserBundle\Parser\KomunalParser;

class KomunalService extends BaseService
{
    public function fillLastIssues($url, \DateTime $date)
    {
        $fields = array(
            'is_send_form_filters'  => 1,
            'status_open_close'     => 'all',
            'OrganId'               => 'ALL',
            'from'                  => $date->format('d-m-Y'),
            'to'                    => $date->format('d-m-Y'),
        );
        $content = $this->postPageContent($url, array(), $fields);
        /** @var KomunalParser $parser */
        $parser = $this->container->get('housefinder.parser.parser.komunal');
        $issues = $parser->fetchAllIssues($content);
        $i = 0;
        if(count($issues) == 0) return $i;
        /** @var EntityManager $em */
        $em = $this->container->get('Doctrine')->getManager();
        foreach($issues as $issue){
            /** @var IssueKomunal $issue */
            try {
                if (is_null($issue->getHouse())) throw new \Exception('House not filled for '.$issue->getDocumentNumber().' '.$issue->getOrganization()->getName());
                $komunal = $this->getKomunalIssue($issue->getOrganization(), $issue->getDocumentNumber(), $issue->getHouse());
                if (!is_null($komunal)) continue;
                $em->persist($issue);
                $em->flush($issue);
                $i++;
            }catch(\Exception $e){

            }
        }
        return $i;
    }

    /**
     * @param Organization $organization
     * @param $documentNumber
     * @param House $house
     * @return KomunalIssue
     */
    public function getKomunalIssue(Organization $organization, $documentNumber, House $house)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('Doctrine')->getManager();
        $issue = $em->getRepository('HouseFinderCoreBundle:IssueKomunal')->findOneBy(array(
            'organization'      => $organization,
            'house'             => $house,
            'documentNumber'    => $documentNumber,
        ));
        return $issue;
    }
}