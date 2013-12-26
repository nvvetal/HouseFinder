<?php
/**
 * Created by PhpStorm.
 * User: boda
 * Date: 16.12.13
 * Time: 18:34
 */

namespace HouseFinder\CoreBundle\Security\Core\User;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use HouseFinder\CoreBundle\Service\UserManagerService;


class FOSUBUserProvider extends BaseProvider
{
    /**
     * @var UserManagerService
     */
    protected $userManagerService;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager FOSUB user provider.
     * @param array $properties Property mapping.
     * @param UserManagerService $userManagerService Core UserManagerService.
     */
    public function __construct(UserManagerInterface $userManager, array $properties, UserManagerService $userManagerService)
    {
        parent::__construct($userManager, $properties);
        $this->userManagerService = $userManagerService;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return parent::loadUserByOAuthUserResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $setter = 'set' . ucfirst($property);

        if (!method_exists($user, $setter)) {
            throw new \RuntimeException(sprintf("Class '%s' should have a method '%s'.", get_class($user), $setter));
        }

        $username = $response->getUsername();

        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $this->userManagerService->mergeUsers($user, $previousUser, $this->userManager);
        }

        $user->$setter($username);

        $this->userManager->updateUser($user);
    }
}
