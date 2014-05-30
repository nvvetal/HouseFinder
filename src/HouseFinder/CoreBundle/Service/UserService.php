<?php

namespace HouseFinder\CoreBundle\Service;

use HouseFinder\CoreBundle\Entity\User;

class UserService
{

    public function getUserREST(User $user)
    {
        $data = array(
            'id'        => $user->getId(),
            'username'  => $user->getUsernameFiltered(),
        );
        return $data;
    }
}
