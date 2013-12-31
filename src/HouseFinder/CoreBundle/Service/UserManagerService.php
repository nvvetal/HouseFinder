<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 26.12.13
 * Time: 14:30
 */

namespace HouseFinder\CoreBundle\Service;


use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use HouseFinder\CoreBundle\Entity\User;
use HouseFinder\CoreBundle\Entity\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManagerService
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function mergeUsers(UserInterface $currentUser, UserInterface $mergedUser, UserManagerInterface $userManager)
    {
        if (!$currentUser instanceof User || !$mergedUser instanceof User) {
            throw new \RuntimeException('$currentUser and $mergedUser must be instances of HouseFinder\CoreBundle\Entity\User');
        }
        if ($currentUser === $mergedUser) {
            return;
        }
        /** @var $userRepository UserRepository */
        $userRepository = $this->em->getRepository('HouseFinder\CoreBundle\Entity\User');
        /** @var $currentUser User */
        /** @var $mergedUser User */
        $userRepository->mergeUsers($currentUser, $mergedUser, $userManager);
    }

}
