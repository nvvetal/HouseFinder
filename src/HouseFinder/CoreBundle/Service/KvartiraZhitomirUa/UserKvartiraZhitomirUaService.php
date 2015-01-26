<?php
namespace HouseFinder\CoreBundle\Service\KvartiraZhitomirUa;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\User;
use HouseFinder\CoreBundle\Entity\UserKvartiraZhitomirUa;
use HouseFinder\CoreBundle\Entity\UserKvartiraZhitomirUaRepository;
use HouseFinder\CoreBundle\Entity\UserPhone;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Entity\UserSlandoRepository;
use HouseFinder\CoreBundle\Service\UserService;
use HouseFinder\ParserBundle\Entity\KvartiraZhitomirUa\KvartiraZhitomirUaParserEntity;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;
use HouseFinder\ParserBundle\Parser\KvartiraZhitomirUaParser;

class UserKvartiraZhitomirUaService
{
    protected $container;
    /** @var EntityManager $em */
    protected $em;
    /** @var UserKvartiraZhitomirUaRepository $repo */
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
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:UserKvartiraZhitomirUa');
        $this->userService = $container->get('housefinder.service.user');
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return UserKvartiraZhitomirUa|null
     */
    public function getOrCreateUserByRaw(KvartiraZhitomirUaParserEntity $raw)
    {
        $user = $this->userService->getUserByRaw($this->getUserHash($raw), $raw);
        if(!is_null($user)) return $user;
        return $this->createUserByRaw($raw);
    }

    /**
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return string
     */
    public function getUserHash(KvartiraZhitomirUaParserEntity $raw)
    {
        $user = null;
        $userHash = 'nohash';
        if(count($raw->getPhones()) > 0){
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
     * @param KvartiraZhitomirUaParserEntity $raw
     * @return UserKvartiraZhitomirUa
     */
    public function createUserByRaw(KvartiraZhitomirUaParserEntity $raw)
    {
        $userHash = $this->getUserHash($raw);
        $user = new UserKvartiraZhitomirUa();
        $userName = $raw->getOwnerName().'@'.$userHash.'@kvartiraZhitomirUa';
        $user->setUsername($userName);
        $user->setUsernameCanonical($userName);
        $user->setEmail($userName);
        $user->setEmailCanonical($userName);
        $user->setSourceHash($userHash);
        $user->setPassword(md5(time()));
        $user->setLocked(true);
        $user->setExpired(true);
        $user->setRoles(array());
        $user->setCredentialsExpired(true);
        if(!is_null($raw->getOwnerType())) $user->setType($raw->getOwnerType());
        $this->userService->fillUserPhones($user, $raw->getPhones());
        $user->setCreated(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
}