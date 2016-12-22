<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlockingLoginListener {

    /**
     * @var string[]
     */
    private $blocks;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var int
     */
    private $blockCount;

    /**
     * BlockingLoginListener constructor.
     *
     * @param string[]      $blocks
     * @param EntityManager $entityManager
     * @param int           $blockCount
     */
    public function __construct($blocks, EntityManager $entityManager, $blockCount = 5) {
        $this->blocks        = $blocks;
        $this->entityManager = $entityManager;
        $this->blockCount    = $blockCount;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {
        if($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        if($this->shouldBlock($event->getRequest()->getClientIp(), $event->getRequest()->attributes->get('_route'))) {
            throw new AccessDeniedException();
        }
    }

    private function shouldBlock($ip, $route) {
        if(in_array(strtolower($route), $this->blocks)) {
            return $this->entityManager->getRepository('BDNUserBundle:Session')->getSessionCount(
                    $ip
                ) >= $this->blockCount;
        }

        return false;
    }
}