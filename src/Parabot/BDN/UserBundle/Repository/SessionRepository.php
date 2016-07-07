<?php

namespace Parabot\BDN\UserBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\Session;

/**
 * SessionRepository
 */
class SessionRepository extends EntityRepository {

    /**
     * @param string $ip   IP of the user trying to access the page
     * @param int    $span Amount of minutes since now, default 5
     *
     * @return array
     */
    public function getSessionCount($ip, $span = 5) {
        $query = $this->createQueryBuilder('s')
            ->where('s.ip = :ip')
            ->andWhere('s.date > :dt')
            ->setParameter('ip', $ip)
            ->setParameter('dt', new \DateTime('-' . intval($span) . ' minutes'), Type::DATETIME)
            ->getQuery();

        $result = $query->getArrayResult();
        if ($result != null && is_array($result)){
            return sizeof($result);
        }
        return 0;
    }

    public function createBlock($ip, $datetime = null){
        if ($datetime === null){
            $datetime = new \DateTime();
        }

        $session = new Session();
        $session->setIp($ip)
            ->setDate($datetime);

        return $session;
    }
}
