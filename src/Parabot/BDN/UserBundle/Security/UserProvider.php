<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use AppBundle\Service\StringUtils;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends BaseClass {

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserProvider constructor.
     *
     * @param UserManagerInterface $userManager
     * @param array                $properties
     * @param UserPasswordEncoder  $passwordEncoder
     * @param EntityManager        $entityManager
     */
    public function __construct(UserManagerInterface $userManager, array $properties, UserPasswordEncoder $passwordEncoder, EntityManager $entityManager) {
        parent::__construct($userManager, $properties);
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }


    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response) {
        $userInfo     = $this->parseResponse($response);
        $username     = $userInfo[ 'username' ];
        $service      = $response->getResourceOwner()->getName();
        $setter       = 'set' . ucfirst($service);
        $setter_id    = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        if(null !== $previousUser = $this->userManager->findUserBy(
                [ 'communityId' => $response->getResponse()[ 'id' ] ]
            )
        ) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    private function parseResponse(UserResponseInterface $response) {
        return [
            'username' => $response->getResponse()[ 'username' ],
            'id'       => $response->getResponse()[ 'id' ],
            'email'    => $response->getResponse()[ 'email' ],
            'groups'   => array_merge([$response->getResponse()['group']], $response->getResponse()['group_others'])
        ];
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return \FOS\UserBundle\Model\UserInterface|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $userInfo = $this->parseResponse($response);
        $user     = $this->userManager->findUserBy([ 'communityId' => $userInfo[ 'id' ] ]);

        if($user === null) {
            $service      = $response->getResourceOwner()->getName();
            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';

            /**
             * @var $user User
             */
            $user = $this->userManager->createUser();
            $user->$setter_id($userInfo[ 'username' ]);
            $user->$setter_token($response->getAccessToken());

            $user->setUsername($userInfo[ 'username' ]);
            $user->setEmail($userInfo[ 'email' ]);

            $password = StringUtils::generateRandomString();
            $user->setPlainPassword($password);
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setEnabled(true);
            $user->setCommunityId($userInfo[ 'id' ]);

            $user = $this->setGroups($user, $userInfo['groups']);

            $this->userManager->updateUser($user);

            return $user;
        } else {
            $user->setUsername($userInfo[ 'username' ]);
            $user->setEmail($userInfo[ 'email' ]);

            $user = $this->setGroups($user, $userInfo['groups']);
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setter      = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param int[]         $ids
     *
     * @return UserInterface
     */
    private function setGroups(UserInterface $user, $ids) {
        $gRepository = $this->entityManager->getRepository('BDNUserBundle:Group');
        $groups      = $gRepository->findAll();

        foreach($ids as $id) {
            foreach($groups as $group) {
                if($group->getCommunityId() == $id) {
                    $user->addGroup($group);
                    break;
                }
            }
        }

        return $user;
    }
}