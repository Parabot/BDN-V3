<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Controller;

use FOS\OAuthServerBundle\Controller\AuthorizeController as BaseAuthorizeController;
use OAuth2\OAuth2ServerException;
use Parabot\BDN\OAuthServerBundle\Form\AuthorizeFormType;
use Parabot\BDN\OAuthServerBundle\Form\Model\Authorize;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorizeController extends BaseAuthorizeController {

    public function authorizeAction(Request $request) {
        $key  = $request->cookies->get($this->container->getParameter('api_key_cookie'));
        $user = $this->container->get('internal_user_manager')->getUser($key);

        if($user != null) {
            if( ! $request->get('client_id')) {
                throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");
            }

            $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
            $client        = $clientManager->findClientByPublicId($request->get('client_id'));

            if( ! ($client instanceof Client)) {
                throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.");
            }

            /**
             * @var FormFactory $formFactory
             */
            $formFactory = $this->container->get('form.factory');
            $authorize   = new Authorize();
            $form        = $formFactory->create(AuthorizeFormType::class, $authorize);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                try {

                    return $this->container->get('fos_oauth_server.server')->finishClientAuthorization(
                        true,
                        $user,
                        $request,
                        null
                    );
                } catch(OAuth2ServerException $e) {
                    return $e->getHttpResponse();
                }
            }

            $response = new Response();
            $response->setContent(
                $this->container->get('twig')->render(
                    'BDNOAuthServerBundle:Authorize:authorize.html.twig',
                    [
                        'form'   => $form->createView(),
                        'client' => $client,
                    ]
                )
            );

            return $response;
        } else {
            return new JsonResponse([ 'result' => 'User not logged in' ], 403);
        }
    }
}