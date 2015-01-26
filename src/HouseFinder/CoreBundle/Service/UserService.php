<?php

namespace HouseFinder\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\User;
use HouseFinder\CoreBundle\Entity\UserKvartiraZhitomirUa;
use HouseFinder\CoreBundle\Entity\UserPhone;
use HouseFinder\CoreBundle\Entity\UserRepository;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\ParserBundle\Entity\BaseParserEntity;

class UserService
{
    protected $container;
    /** @var EntityManager $em */
    protected $em;
    /** @var UserRepository $repo */
    protected $repo;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:User');
    }

    /**
     * @param BaseParserEntity $raw
     * @return null|User|UserSlando|UserKvartiraZhitomirUa
     */
    public function getUserByPhones(BaseParserEntity $raw)
    {
        $userPhone = null;
        $user = null;
        foreach ($raw->getPhones() as $msisdn){
            $userPhone = $this->em->getRepository('HouseFinderCoreBundle:UserPhone')
                ->findOneBy(array('msisdn' => $msisdn));
            if(!is_null($userPhone)) break;
        }
        if(!is_null($userPhone)){
            $user = $userPhone->getUser();
        }
        return $user;
    }

    /**
     * @param User $user
     * @param array $phones
     * @return bool
     */
    public function fillUserPhones(User &$user, $phones)
    {
        $user->setPhoneUpdated(new \DateTime());
        if(count($phones) == 0) return false;
        foreach ($phones as $msisdn){
            $phone = new UserPhone();
            $phone->setMsisdn($msisdn);
            $user->addPhone($phone);
        }
        return true;
    }

    /**
     * @param string $hash
     * @return UserSlando
     */
    public function getUserByHash($hash)
    {
        return $this->repo->fetchByHash($hash);
    }

    /**
     * @param $hash
     * @param BaseParserEntity $raw
     * @return User|null
     */
    public function getUserByRaw($hash, BaseParserEntity $raw)
    {
        $user = $this->getUserByHash($hash);
        if(is_null($user)) return null;
        if(is_null($user->getPhoneUpdated())){
            $this->fillUserPhones($user, $raw->getPhones());
            $this->em->flush();
        }
        return $user;
    }
}
