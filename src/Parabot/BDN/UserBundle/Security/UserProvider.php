<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use AppBundle\Service\StringUtils;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends BaseClass {
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response) {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        $service      = $response->getResourceOwner()->getName();
        $setter       = 'set' . ucfirst($service);
        $setter_id    = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        if(null !== $previousUser = $this->userManager->findUserBy([ 'communityId' => $response->getResponse()['id'] ])) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return \FOS\UserBundle\Model\UserInterface|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $username = $response->getUsername();
        $user     = $this->userManager->findUserBy([ 'communityId' => $response->getResponse()[ 'id' ] ]);

        if($user === null) {
            $service      = $response->getResourceOwner()->getName();
            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';

            /**
             * @var $user User
             */
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());

            $user->setUsername($username);
            $user->setEmail($response->getEmail());
            $user->setPassword(StringUtils::generateRandomString());
            $user->setEnabled(true);
            $user->setCommunityId($response->getResponse()['id']);

            $this->userManager->updateUser($user);

            return $user;
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setter      = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param int[]         $ids
     * @param Group[]       $groups
     */
    private function setGroups(UserInterface $user, $ids, $groups) {
        foreach($ids as $id) {
            foreach($groups as $group) {
                if($group->getCommunityId() == $id) {
                    $user->addGroup($group);
                    break;
                }
            }
        }
    }
}