<?php

namespace HouseFinder\CoreBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertisementRepository extends EntityRepository
{
    public function search($params)
    {
        $page = isset($params['data']['page']) ? (int) $params['data']['page'] : '';
        $em = $this->getEntityManager();
        $q = $em->getRepository('HouseFinderCoreBundle:Advertisement')->createQueryBuilder('a');
        $q->innerJoin('a.address', 'address');
        if(!empty($params['data']['price_from'])){
            $q->andWhere('a.price >= :priceFrom');
            $q->setParameter(':priceFrom', $params['data']['price_from']);
        }
        if(!empty($params['data']['price_to'])){
            $q->andWhere('a.price <= :priceTo');
            $q->setParameter(':priceTo', $params['data']['price_to']);
        }
        if(!empty($params['data']['rooms_from']) || !empty($params['data']['rooms_to'])){
            $q->innerJoin('a.rooms', 'r');
            if(!empty($params['data']['rooms_from'])){
                $q->andHaving('COUNT(r.id) >= :roomsFrom');
                $q->setParameter(':roomsFrom', $params['data']['rooms_from']);
            }
            if(!empty($params['data']['rooms_to'])){
                $q->andHaving('COUNT(r.id) <= :roomsTo');
                $q->setParameter(':roomsTo', $params['data']['rooms_to']);
            }
        }
        if(!empty($params['data']['space_from'])){
            $q->andWhere('a.fullSpace >= :spaceFrom');
            $q->setParameter(':spaceFrom', $params['data']['space_from']);
        }
        if(!empty($params['data']['space_to'])){
            $q->andWhere('a.fullSpace <= :spaceTo');
            $q->setParameter(':spaceTo', $params['data']['space_to']);
        }
        if(!empty($params['data']['space_living_from'])){
            $q->andWhere('a.livingSpace >= :spaceFrom');
            $q->setParameter(':spaceFrom', $params['data']['space_living_from']);
        }
        if(!empty($params['data']['space_living_to'])){
            $q->andWhere('a.livingSpace <= :spaceTo');
            $q->setParameter(':spaceTo', $params['data']['space_living_to']);
        }

        if(!empty($params['data']['type']) &&  $params['data']['type'] != Advertisement::WALL_TYPE_ALL){
            $q->andWhere('a.wallType = :type');
            $q->setParameter(':type', $params['data']['type']);
        }

        if(!empty($params['data']['level_from'])){
            $q->andWhere('a.level >= :levelFrom');
            $q->setParameter(':levelFrom', $params['data']['level_from']);
        }
        if(!empty($params['data']['level_to'])){
            $q->andWhere('a.level <= :levelTo');
            $q->setParameter(':levelTo', $params['data']['level_to']);
        }

        if(!empty($params['data']['house_level_from'])){
            $q->andWhere('a.maxLevels >= :levelFrom');
            $q->setParameter(':levelFrom', $params['data']['house_level_from']);
        }
        if(!empty($params['data']['house_level_to'])){
            $q->andWhere('a.maxLevels <= :levelTo');
            $q->setParameter(':levelTo', $params['data']['house_level_to']);
        }

        if(!empty($params['data']['city_id'])){
            $q->andWhere('address.locality = :cityId');
            $q->setParameter(':cityId', $params['data']['city_id']);
        }

        if(!empty($params['data']['period'])){
            switch($params['data']['period']){
                case 'week':
                    $q->andWhere('a.created BETWEEN :periodBegin AND :periodEnd');

                    break;

                case 'month':

                    break;
            }
        }

        $q->groupBy('a.id');
        $q->orderBy('a.created', 'DESC');
        $q->setFirstResult($page*$params['perPage']);
        $q->setMaxResults($params['perPage']);
        //echo $q->getQuery()->getSQL();        exit;
        $paginator = new Paginator($q, $fetchJoinCollection = true);
        $c = count($paginator);
        return array(
            'items' => $paginator,
            'pages' => ceil($c / $params['perPage']),
            'count' => $c
        );
    }
}
