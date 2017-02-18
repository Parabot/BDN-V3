<?php

namespace Parabot\BDN\BotBundle\Controller\Bot;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackNotificationController extends Controller {

    /**
     * @Route("/send")
     *
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function sendAction(Request $request) {
        $attachment = $this->get('slack_manager')->createAttachment(
            'Script notification',
            'Example Script',
            'good',
            '',
            [ 'Message' => 'Out of health', 'Health' => 97 ]
        );

        $this->get('slack_manager')->sendMessage('', [ $attachment ], '@emmastone');

        return new JsonResponse();
    }

    /**
     * @Route("/send/{scriptId}")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @param int     $scriptId
     *
     * @return JsonResponse
     */
    public function sendScriptNotificationAction(Request $request, $scriptId) {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Script');
        if(($script = $repository->findOneBy([ 'id' => $scriptId ])) !== null) {
            $requiredFields = [
                'Message' => null,
            ];
            $fields         = [];

            foreach($requiredFields as $field => $value) {
                if(($value = $request->get($field)) != null) {
                    $fields[ $field ] = $value;
                } else {
                    return new JsonResponse([ 'result' => 'Missing required parameter (' . $field . ')' ], 400);
                }
            }

            foreach($request->request->all() as $key => $value) {
                if(substr($key, 0, 2) === 'f_') {
                    $key = substr($key, 2, strlen($key));
                    foreach($requiredFields as $i => $j) {
                        if($i != $key) {
                            $fields[ $key ] = $value;
                        }
                    }
                }
            }

            $attachment = $this->get('slack_manager')->createAttachment(
                'Script notification',
                $script->getName(),
                'good',
                '',
                $fields
            );

            $result = $this->get('slack_manager')->sendMessage('', [ $attachment ], '@emmastone');
            if($result != null && $result->getStatus() === true) {
                return new JsonResponse([ 'result' => 'Notification sent' ]);
            } else {
                return new JsonResponse([ 'result' => 'Notification could not be send' ], 500);
            }
        } else {
            return new JsonResponse([ 'result' => 'Unknown script ID given' ], 404);
        }
    }
}
