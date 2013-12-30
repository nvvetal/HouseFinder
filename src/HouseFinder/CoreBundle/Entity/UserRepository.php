<?php
namespace HouseFinder\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Created by PhpStorm.
 * User: boda
 * Date: 26.12.13
 * Time: 15:25
 */
class UserRepository extends EntityRepository
{
    public function mergeUsers(User $currentUser, User $mergedUser, UserManagerInterface $userManager)
    {
        //TODO: merge users
        $currentUser->addEmails($mergedUser->getEmails());
        $userManager->deleteUser($mergedUser);
    }
}
