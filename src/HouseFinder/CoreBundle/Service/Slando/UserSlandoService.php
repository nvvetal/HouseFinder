<?php
namespace HouseFinder\CoreBundle\Service\Slando;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\UserPhone;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Entity\UserSlandoRepository;
use HouseFinder\CoreBundle\Service\UserService;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;

class UserSlandoService
{
    protected $container;
    /** @var EntityManager $em */
    protected $em;
    /** @var UserSlandoRepository $repo */
    protected $repo;
    /** @var UserService $userService */
    protected $userService;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:UserSlando');
        $this->userService = $container->get('housefinder.service.user');
    }

    /**
     * @param SlandoParserEntity $raw
     * @return UserSlando|null
     */
    public function getOrCreateUserByRaw(SlandoParserEntity $raw)
    {
        $userSlando = $this->userService->getUserByRaw($this->getUserHash($raw), $raw);
        if(!is_null($userSlando)) return $userSlando;
        return $this->createUserByRaw($raw);
    }

    /**
     * @param SlandoParserEntity $raw
     * @return string
     */
    public function getUserHash(SlandoParserEntity $raw)
    {
        $userHash = $raw->getOwnerHash();
        $user = null;
        if(empty($userHash)) $userHash = 'nohash';
        if(empty($userHash) && count($raw->getPhones()) > 0){
            $user = $this->userService->getUserByPhones($raw);
            if(is_null($user)){
                $phones = $raw->getPhones();
                $userHash = 'byPhone:'.$phones[0];
            }else{
                $userHash = $user->getSourceHash();
            }
        }
        return $userHash;
    }

    /**
     * @param SlandoParserEntity $raw
     * @return UserSlando
     */
    public function createUserByRaw(SlandoParserEntity $raw)
    {
        $userHash = $this->getUserHash($raw);
        $UserSlando = new UserSlando();
        $UserSlandoName = $raw->getOwnerName().'@'.$userHash.'@slando';
        $UserSlando->setUsername($UserSlandoName);
        $UserSlando->setUsernameCanonical($UserSlandoName);
        $UserSlando->setEmail($UserSlandoName);
        $UserSlando->setEmailCanonical($UserSlandoName);
        $UserSlando->setSourceHash($userHash);
        $UserSlando->setSourceURL($raw->getOwnerUrl());
        $UserSlando->setPassword(md5(time()));
        $UserSlando->setLocked(true);
        $UserSlando->setExpired(true);
        $UserSlando->setRoles(array());
        $UserSlando->setCredentialsExpired(true);
        if(!is_null($raw->getOwnerType())) $UserSlando->setType($raw->getOwnerType());
        $this->userService->fillUserPhones($UserSlando, $raw->getPhones());
        $UserSlando->setCreated(new \DateTime());
        $this->em->persist($UserSlando);
        $this->em->flush();
        return $UserSlando;
    }

}
