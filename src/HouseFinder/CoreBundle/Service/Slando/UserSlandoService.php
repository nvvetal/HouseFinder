<?php
namespace HouseFinder\CoreBundle\Service\Slando;

use Doctrine\ORM\EntityManager;
use HouseFinder\CoreBundle\Entity\UserPhone;
use HouseFinder\CoreBundle\Entity\UserSlando;
use HouseFinder\CoreBundle\Entity\UserSlandoRepository;
use HouseFinder\ParserBundle\Entity\Slando\SlandoParserEntity;

class UserSlandoService
{
    protected $container;
    /** @var EntityManager $em */
    protected $em;
    /** @var UserSlandoRepository $repo */
    protected $repo;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->repo = $this->em->getRepository('HouseFinderCoreBundle:UserSlando');
    }

    /**
     * @param string $hash
     * @return UserSlando
     */
    public function getUserByHash($hash)
    {
        return $this->repo->findUserByHash($hash);
    }

    /**
     * @param SlandoParserEntity $raw
     * @return null|UserSlando
     */
    private function getSlandoUserByPhones(SlandoParserEntity $raw)
    {
        $UserSlandoPhone = null;
        $UserSlando = null;
        foreach ($raw->getPhones() as $msisdn){
            $UserSlandoPhone = $this->em->getRepository('HouseFinderCoreBundle:UserPhone')
                ->findOneBy(array('msisdn' => $msisdn));
            if(!is_null($UserSlandoPhone)) break;
        }
        if(!is_null($UserSlandoPhone)){
            $UserSlando = $UserSlandoPhone->getUser();
        }
        return $UserSlando;
    }

    /**
     * @param SlandoParserEntity $raw
     * @return UserSlando|null
     */
    public function getOrCreateUserByRaw(SlandoParserEntity $raw)
    {
        $userSlando = $this->getUserByRaw($raw);
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
        $UserSlando = null;
        if(empty($userHash)) $userHash = 'nohash';
        if(empty($userHash) && count($raw->getPhones()) > 0){
            $UserSlando = $this->getSlandoUserByPhones($raw);
            if(is_null($UserSlando)){
                $phones = $raw->getPhones();
                $userHash = 'byPhone:'.$phones[0];
            }else{
                $userHash = $UserSlando->getSourceHash();
            }
        }
        return $userHash;
    }

    /**
     * @param SlandoParserEntity $raw
     * @return UserSlando|null
     */
    public function getUserByRaw(SlandoParserEntity $raw)
    {
        $userHash = $this->getUserHash($raw);
        $UserSlando = $this->getUserByHash($userHash);
        if(is_null($UserSlando)) return null;
        //TODO: change to other function?
        if(is_null($UserSlando->getPhoneUpdated())){
            $this->fillUserPhones($UserSlando, $raw->getPhones());
            $this->em->flush();
        }
        return $UserSlando;
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
        $this->fillUserPhones($UserSlando, $raw->getPhones());
        $UserSlando->setCreated(new \DateTime());
        $this->em->persist($UserSlando);
        $this->em->flush();
        return $UserSlando;
    }

    /**
     * @param UserSlando $UserSlando
     * @param array $phones
     * @return bool
     */
    private function fillUserPhones(UserSlando &$UserSlando, $phones)
    {
        $UserSlando->setPhoneUpdated(new \DateTime());
        if(count($phones) == 0) return false;
        foreach ($phones as $msisdn){
            $phone = new UserPhone();
            $phone->setMsisdn($msisdn);
            $UserSlando->addPhone($phone);
        }
        return true;
    }

}
