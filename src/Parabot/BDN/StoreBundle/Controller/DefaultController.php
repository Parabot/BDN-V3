<?php

namespace Parabot\BDN\StoreBundle\Controller;

use AppBundle\Entity\Dependencies\Script;
use Parabot\BDN\StoreBundle\Entity\Order;
use Parabot\BDN\StoreBundle\Entity\OrderItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Framework;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    const STATE_BEGIN    = 'begin';
    const STATE_COMPLETE = 'complete';

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name) {
        return [ 'name' => $name ];
    }

    /**
     * @Framework\Route("/begin-order-for-download/{id}", name="begin_order_for_download")
     */
    public function beginOrderForDownloadAction(Request $request, $id) {

        $script = $this->getDoctrine()->getRepository(Script::class)->findOneBy([ 'id' => $id ]);

        $order = new Order();
        $form  = $this->createForm(new OrderType(), $order);

        if('POST' === $request->getMethod()) {
            $order->setState(self::STATE_BEGIN);

            $orderItem = new OrderItem();
            $orderItem->setScript($script);
            $orderItem->setOrder($order);
            $orderItem->setUnitPrice(59);
            // $orderItem->setImmutable(true); // Need to verify how this affects behavior.

            $this->get('event_dispatcher')->dispatch('app.download_ordered', new GenericEvent($order));

            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            if($form->isValid()) {
                $order->setState(self::STATE_COMPLETE);
                $this->addFlash('order.state', self::STATE_COMPLETE);

                $em->persist($order);
                $em->flush();

                return $this->redirectToRoute(
                    'complete_order',
                    [
                        'id' => $order->getId(),
                    ]
                );
            }
        }

        return $this->render(
            'AppBundle::begin_order_for_download.html.twig',
            [
                'form'     => $form->createView(),
                'download' => $script,
            ]
        );
    }
}
