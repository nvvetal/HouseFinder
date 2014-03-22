<?php

namespace HouseFinder\CoreBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertisementRepository extends EntityRepository
{
    public function search($params)
    {
        $em = $this->getEntityManager();
        $q = $em->getRepository('HouseFinderCoreBundle:Advertisement')->createQueryBuilder('a');
        $q->orderBy('a.created', 'DESC');
        $q->setFirstResult($params['page']*$params['perPage']);
        $q->setMaxResults($params['perPage']);
        $paginator = new Paginator($q, $fetchJoinCollection = true);
        $c = count($paginator);
        return array(
            'items' => $paginator,
            'pages' => ceil($c / $params['perPage'])
        );
    }
}
