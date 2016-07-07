<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationListener implements AuthenticationFailureHandlerInterface, AuthenticationSuccessHandlerInterface {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AuthenticationListener constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $session = $this->entityManager->getRepository('BDNUserBundle:Session')->createBlock($request->getClientIp());
        $this->entityManager->persist($session);
        $this->entityManager->flush();

        return new JsonResponse([ 'result' => $exception->getMessage() ], 401);
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        return new JsonResponse([ 'result' => 'ok' ]);
    }
}