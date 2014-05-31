<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertisementRepository extends EntityRepository
{
    public function search(DataContainer $params)
    {
        $page = isset($params->page) ? (int) $params->page : 0;
        $limit = isset($params->limit) && $params->limit > 0 ? $params->limit : 30;
        //var_dump($params);exit;
        $em = $this->getEntityManager();
        $q = $em->getRepository('HouseFinderCoreBundle:Advertisement')->createQueryBuilder('a');
        $q->innerJoin('a.address', 'address');
        if(isset($params->price_from)){
            $q->andWhere('a.price >= :priceFrom');
            $q->setParameter(':priceFrom', $params->price_from);
        }
        if(isset($params->price_to)){
            $q->andWhere('a.price <= :priceTo');
            $q->setParameter(':priceTo', $params->price_to);
        }
        if(isset($params->rooms_from) || isset($params->rooms_to)){
            $q->innerJoin('a.rooms', 'r');
            if(!empty($params['rooms_from'])){
                $q->andHaving('COUNT(r.id) >= :roomsFrom');
                $q->setParameter(':roomsFrom', $params->rooms_from);
            }
            if(isset($params->rooms_to)){
                $q->andHaving('COUNT(r.id) <= :roomsTo');
                $q->setParameter(':roomsTo', $params->rooms_to);
            }
        }
        if(isset($params->space_from)){
            $q->andWhere('a.fullSpace >= :spaceFrom');
            $q->setParameter(':spaceFrom', $params->space_from);
        }
        if(isset($params->space_to)){
            $q->andWhere('a.fullSpace <= :spaceTo');
            $q->setParameter(':spaceTo', $params->space_to);
        }
        if(isset($params->space_living_from)){
            $q->andWhere('a.livingSpace >= :spaceFrom');
            $q->setParameter(':spaceFrom', $params->space_living_from);
        }
        if(isset($params->space_living_to)){
            $q->andWhere('a.livingSpace <= :spaceTo');
            $q->setParameter(':spaceTo', $params->space_living_to);
        }

        if(isset($params->type) &&  $params->type != Advertisement::WALL_TYPE_ALL){
            $q->andWhere('a.wallType = :type');
            $q->setParameter(':type', $params->type);
        }

        if(isset($params->level_from)){
            $q->andWhere('a.level >= :levelFrom');
            $q->setParameter(':levelFrom', $params->level_from);
        }
        if(isset($params->level_to)){
            $q->andWhere('a.level <= :levelTo');
            $q->setParameter(':levelTo', $params->level_to);
        }

        if(isset($params->house_level_from)){
            $q->andWhere('a.maxLevels >= :levelFrom');
            $q->setParameter(':levelFrom', $params->house_level_from);
        }
        if(isset($params->house_level_to)){
            $q->andWhere('a.maxLevels <= :levelTo');
            $q->setParameter(':levelTo', $params->house_level_to);
        }

        if(isset($params->city_id)){
            $q->andWhere('address.locality = :cityId');
            $q->setParameter(':cityId', $params->city_id);
        }

        if(isset($params->period)){
            switch($params->period){
                case 'week':
                    $q->andWhere('a.created BETWEEN :periodBegin AND :periodEnd');
                    $start = new \DateTime();
                    $start->setTimestamp(strtotime('-7 day'));
                    $end = new \DateTime();
                    $q->setParameter(':periodBegin', $start);
                    $q->setParameter(':periodEnd', $end);
                    break;

                case 'month':
                    $q->andWhere('a.created BETWEEN :periodBegin AND :periodEnd');
                    $start = new \DateTime();
                    $start->setTimestamp(strtotime('-1 month'));
                    $end = new \DateTime();
                    $q->setParameter(':periodBegin', $start);
                    $q->setParameter(':periodEnd', $end);
                    break;
            }
        }

        $q->groupBy('a.id');
        $q->orderBy('a.created', 'DESC');
        $q->setFirstResult($page*$limit);
        $q->setMaxResults($limit);
        $paginator = new Paginator($q, $fetchJoinCollection = true);
        $c = count($paginator);
        return array(
            'items' => $paginator,
            'pages' => ceil($c / $limit),
            'count' => $c
        );
    }

    public function findByFresh(DataContainer $params)
    {
        $em = $this->getEntityManager();
        try {
            $q = $em->getRepository('HouseFinderCoreBundle:Advertisement')->createQueryBuilder('a');
            $q->innerJoin('a.address', 'address');
            if(isset($params->city_id)){
                $q->andWhere('address.locality = :cityId');
                $q->setParameter(':cityId', $params->city_id);
            }

            if(isset($params->type)){
                switch($params->type){
                    case "new":
                        $q->andWhere('a.created BETWEEN :start AND :end');
                        $start = new \DateTime();
                        $start->setTimestamp(time() - 3*24*3600);
                        $end = new \DateTime();
                        $q->setParameter(':start', $start);
                        $q->setParameter(':end', $end);
                        break;
                    case "normal":
                        $q->andWhere('a.created BETWEEN :start AND :end');
                        $start = new \DateTime();
                        $start->setTimestamp(time() - 7*24*3600);
                        $end = new \DateTime();
                        $q->setParameter(':start', $start);
                        $q->setParameter(':end', $end);
                        break;
                    case "old":
                        $q->andWhere('a.created BETWEEN :start AND :end');
                        $start = new \DateTime();
                        $start->setTimestamp(strtotime('-1 month'));
                        $end = new \DateTime();
                        $q->setParameter(':start', $start);
                        $q->setParameter(':end', $end);
                        break;
                }
            }
            $q->andWhere('address.streetNumber IS NOT NULL');
            $q->groupBy('a.id');
            $q->orderBy('a.created', 'DESC');
            $data = $q->getQuery()->getResult();
        }catch(\Exception $e){
        }
        return $data;
    }
}
