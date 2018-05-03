<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use AppBundle\Service\StringUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Service\LoginRequestManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends BaseClass
{

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var LoginRequestManager
     */
    private $loginRequestManager;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * UserProvider constructor.
     *
     * @param UserManagerInterface $userManager
     * @param array $properties
     * @param UserPasswordEncoder $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param LoginRequestManager $loginRequestManager
     * @param RequestStack $requestStack
     */
    public function __construct(
        UserManagerInterface $userManager,
        array $properties,
        UserPasswordEncoder $passwordEncoder,
        LoginRequestManager $loginRequestManager,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($userManager, $properties);
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->loginRequestManager = $loginRequestManager;
        $this->request = $requestStack->getCurrentRequest();
    }


    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $userInfo = $this->parseResponse($response);
        $username = $userInfo['username'];
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        if (null !== $previousUser = $this->userManager->findUserBy(
                ['communityId' => $response->getData()['id']]
            )) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    private function parseResponse(UserResponseInterface $response)
    {
        return [
            'username' => $response->getData()['username'],
            'id' => $response->getData()['id'],
            'email' => $response->getData()['email'],
            'groups' => array_merge(
                [$response->getData()['group']],
                $response->getData()['group_others']
            ),
        ];
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return \FOS\UserBundle\Model\UserInterface|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userInfo = $this->parseResponse($response);
        $user = $this->userManager->findUserBy(['communityId' => $userInfo['id']]);

        if ($user === null) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            /**
             * @var $user User
             */
            $user = $this->userManager->createUser();
            $user->$setter_id($userInfo['username']);
            $user->$setter_token($response->getAccessToken());

            $user->setUsername($userInfo['username']);
            $user->setEmail($userInfo['email']);

            $password = StringUtils::generateRandomString();
            $user->setPlainPassword($password);
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setEnabled(true);
            $user->setCommunityId($userInfo['id']);

            $user = $this->setGroups($user, $userInfo['groups']);

            $this->userManager->updateUser($user);
            $this->assignCookie($user);

        } else {
            $user->setUsername($userInfo['username']);
            $user->setEmail($userInfo['email']);

            $user = $this->setGroups($user, $userInfo['groups']);

            $this->assignCookie($user);

            $serviceName = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($serviceName).'AccessToken';
            $user->$setter($response->getAccessToken());
        }

        if ($user->getApiKey() == null || strlen($user->getApiKey()) < 128) {
            $apiKey = hash('sha512', StringUtils::generateRandomString(25));
            $user->setApiKey($apiKey);
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param int[] $ids
     *
     * @return UserInterface
     */
    private function setGroups(UserInterface $user, $ids)
    {
        $gRepository = $this->entityManager->getRepository('BDNUserBundle:Group');
        $groups = $gRepository->findAll();

        foreach ($ids as $id) {
            foreach ($groups as $group) {
                if ($group->getCommunityId() == $id) {
                    $user->addGroup($group);
                    break;
                }
            }
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     */
    private function assignCookie($user)
    {
        if ($this->request->cookies->has(LoginRequestManager::KEY_COOKIE)) {
            $this->loginRequestManager->assignUserToKey(
                $this->request->cookies->get(LoginRequestManager::KEY_COOKIE),
                $user
            );
        }
    }
}