<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Service;

use AppBundle\Service\StringUtils;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Util\UserManipulator;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Caller\ApiCallerInterface;
use Parabot\BDN\UserBundle\Entity\CommunityUser;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class Connector {
    use ContainerAwareTrait;

    private static $connection_information = [
        'columns' => [
            'member_id',
            'name',
            'member_group_id',
            'email',
            'mgroup_others',
            'members_pass_hash',
            'members_pass_salt',
        ],
        'table'   => 'core_members',
    ];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * Connector constructor.
     *
     * @param Connection          $connection
     * @param EntityManager       $entityManager
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(
        Connection $connection,
        EntityManager $entityManager,
        UserPasswordEncoder $passwordEncoder
    ) {
        $this->connection      = $connection;
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * TODO: Check last visit, don't sync again
     *
     * @param null|int $timestamp
     *
     * @return string[][]
     */
    public function updateCommunityUsers($timestamp = null) {
        if($timestamp == null) {
            $timestamp = time() - (5 * 60);
        }

        $query = 'SELECT ' . implode(', ', self::$connection_information[ 'columns' ]);
        $query .= ' FROM ' . self::$connection_information[ 'table' ] . ' WHERE last_activity > :time';

        $statement = $this->connection->prepare($query);
        $statement->bindValue('time', $timestamp);
        $statement->execute();

        $result     = $statement->fetchAll();
        $repository = $this->entityManager->getRepository('BDNUserBundle:User');

        $gRepository = $this->entityManager->getRepository('BDNUserBundle:Group');
        $groups      = $gRepository->findAll();

        $newUsers = [ 'new' => [ ], 'updated' => [ ] ];
        $cUser    = null;

        foreach($result as $member) {
            if(($user = $repository->getUserByCommunityMemberId($member[ 'member_id' ])) == null) {
                $cUser = new CommunityUser();

                $cUser = $this->updateInformation($cUser, $member);

                $user = new User();

                $this->updateUserPassword($user, StringUtils::generateRandomString()); // Temporary password

                $user->setCommunityUser($cUser);

                $user = $this->parseCommunityUserToUser($user, $groups);

                $newUsers[ 'new' ][] = $user->getUsername();
            } else {
                if($user !== null) {
                    $cUser = $this->updateInformation($user->getCommunityUser(), $member);
                    $user->setCommunityUser($cUser);

                    $user = $this->parseCommunityUserToUser($user, $groups);

                    $newUsers[ 'updated' ][] = $user->getUsername();
                }
            }

            if($user != null) {
                $this->entityManager->persist($user);
            }

            if($cUser != null) {
                $this->entityManager->persist($cUser);
            }
        }
        $this->entityManager->flush();

        return $newUsers;
    }

    /**
     * @param CommunityUser $cUser
     * @param string        $member
     *
     * @return CommunityUser
     */
    private function updateInformation(CommunityUser $cUser, $member) {
        $cUser->setEmail($member[ 'email' ])->setMemberGroupId($member[ 'member_group_id' ])->setMgroupOthers(
            $member[ 'mgroup_others' ]
        )->setMembersPassHash($member[ 'members_pass_hash' ])->setMembersPassSalt(
            $member[ 'members_pass_salt' ]
        )->setName($member[ 'name' ])->setMemberId($member[ 'member_id' ]);

        return $cUser;
    }

    /**
     * @param User   $user
     * @param string $input
     */
    public function updateUserPassword(User $user, $input) {
        $user->setPlainPassword($input);
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    /**
     * @param User    $user
     *
     * @param Group[] $groups
     *
     * @return User
     */
    private function parseCommunityUserToUser($user, $groups) {
        $cUser   = $user->getCommunityUser();
        $oGroups = [ ];
        if(($go = $cUser->getMgroupOthers()) != null && strlen($go)) {
            $oGroups = explode(',', $go);
        }
        $ids = array_merge([ $cUser->getMemberGroupId() ], $oGroups);

        foreach($ids as $id) {
            foreach($groups as $group) {
                if($group->getCommunityId() == $id) {
                    $user->addGroup($group);
                    break;
                }
            }
        }

        $user->setEmail($cUser->getEmail());
        $user->setUsername($cUser->getName());

        return $user;
    }

    /**
     * Comparing the saved community password with the encryption method of IPB 4
     *
     * @param CommunityUser $user
     * @param string        $input Given password of user
     *
     * @return bool
     */
    public function compareCommunityPassword(CommunityUser $user, $input) {
        return $user->getMembersPassHash() === crypt($input, '$2a$13$' . $user->getMembersPassSalt());
    }
}