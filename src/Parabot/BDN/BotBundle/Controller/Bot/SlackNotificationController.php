<?php

namespace Parabot\BDN\BotBundle\Controller\Bot;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/bot/notifications/slack")
 *
 * Class SlackNotificationController
 * @package Parabot\BDN\BotBundle\Controller\Bot
 */
class SlackNotificationController extends Controller
{
    /**
     * @ApiDoc(
     *  description="Sends a notification to the logged in user over Slack",
     *  requirements={
     *      {
     *          "name"="scriptId",
     *          "dataType"="integer",
     *          "description"="Script ID used to send a notification"
     *      },
     *      {
     *          "name"="Message",
     *          "dataType"="string",
     *          "description"="Message used in the notification"
     *      }
     *  }
     * )
     *
     * @Route("/send/{scriptId}")
     * @Method({"POST"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @param int $scriptId
     *
     * @return JsonResponse
     */
    public function sendScriptNotificationAction(Request $request, $scriptId)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Script');
        if (($script = $repository->findOneBy(['id' => $scriptId])) !== null) {
            $requiredFields = [
                'Message' => null,
            ];
            $fields = [];

            foreach ($requiredFields as $field => $value) {
                if (($value = $request->get($field)) != null) {
                    $fields[$field] = $value;
                } else {
                    return new JsonResponse(['result' => 'Missing required parameter ('.$field.')'], 400);
                }
            }

            foreach ($request->request->all() as $key => $value) {
                if (substr($key, 0, 2) === 'f_') {
                    $key = substr($key, 2, strlen($key));
                    foreach ($requiredFields as $i => $j) {
                        if ($i != $key) {
                            $fields[$key] = $value;
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

            $slackUsername = $this->get('slack_manager')->getUsername(
                $this->get('request_access_evaluator')->getUser()
            );
            if ($slackUsername != null) {
                $result = $this->get('slack_manager')->sendMessage('', [$attachment], $slackUsername);
                if ($result != null && $result->getStatus() === true) {
                    return new JsonResponse(['result' => 'Notification sent']);
                } else {
                    return new JsonResponse(['result' => 'Notification could not be send'], 500);
                }
            } else {
                return new JsonResponse(['result' => 'User not found in Slack'], 404);
            }
        } else {
            return new JsonResponse(['result' => 'Unknown script ID given'], 404);
        }
    }
}
