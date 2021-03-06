<?php

namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use HouseFinder\CoreBundle\Entity\Pager\AdvertisementPager;

class AdvertisementRepository extends EntityRepository
{
    /**
     * @param DataContainer $params
     * @return AdvertisementPager
     */
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

        if(isset($params->ad_type)){
            $q->andWhere('a.type = :type');
            $q->setParameter(':type', $params->ad_type);
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
        $response = new AdvertisementPager();
        $response->setCount($c);
        $response->setPages(ceil($c / $limit));
        if($paginator->count() == 0) return $response;
        foreach($paginator as $advertisement){
            $response->addItem($advertisement);
        }
        return $response;
    }

    public function findByFresh(DataContainer $params)
    {
        $em = $this->getEntityManager();
        $data = null;
        try {
            $q = $em->getRepository('HouseFinderCoreBundle:Advertisement')->createQueryBuilder('a');
            $q->innerJoin('a.address', 'address');
            if(isset($params->city_id)){
                $q->andWhere('address.locality = :cityId');
                $q->setParameter(':cityId', $params->city_id);
            }
            if(isset($params->ad_type)){
                $q->andWhere('a.type = :type');
                $q->setParameter(':type', $params->ad_type);
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
            $q->andWhere('address.streetNumber IS NOT NULL');
            $q->groupBy('a.id');
            $q->orderBy('a.created', 'DESC');
            $data = $q->getQuery()->getResult();
        }catch(\Exception $e){
        }
        return $data;
    }

    /**
     * @param Advertisement $advertisement
     * @param \DateTime $created
     * @return mixed
     */
    public function findAdvertisementPublish(Advertisement $advertisement, \DateTime $created)
    {
        $q = $this->getEntityManager()->getRepository('HouseFinderCoreBundle:AdvertisementPublish')->createQueryBuilder('p');
        $q->andWhere('p.advertisement = :advertisement AND p.created = :created');
        $q->setParameters(array(
            ':advertisement' => $advertisement,
            ':created' => $created,
        ));
        return $q->getQuery()->getOneOrNullResult();
    }
}
