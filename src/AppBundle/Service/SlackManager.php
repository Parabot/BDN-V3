<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use AppBundle\Entity\Slack\MessageAttachment;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DZunke\SlackBundle\Slack\Client;
use DZunke\SlackBundle\Slack\Messaging;
use DZunke\SlackBundle\Slack\Messaging\IdentityBag;
use Guzzle\Common\Event;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Entity\Users\SlackInvite;

class SlackManager
{

    /**
     * @var Messaging
     */
    private $messenger;

    /**
     * @var IdentityBag
     */
    private $identityBag;

    /**
     * @var Client\Connection
     */
    private $connection;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SlackManager constructor.
     *
     * @param Messaging $messenger
     * @param IdentityBag $identityBag
     * @param Client\Connection $connection
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        Messaging $messenger,
        IdentityBag $identityBag,
        Client\Connection $connection,
        EntityManagerInterface $entityManager
    ) {
        $this->messenger = $messenger;
        $this->identityBag = $identityBag;
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $title
     * @param string $message
     * @param string $link
     * @param string[] $fields
     * @param string $channel
     *
     * @return bool|Client\Response
     */
    public function sendSuccessMessage($title, $message, $link = '', $fields = [], $channel = '')
    {
        $attachment = $this->createAttachment($title, $message, 'good', $link, $fields);

        return $this->sendMessage('', [$attachment], $channel);
    }

    /**
     * @param          $title
     * @param string $message
     * @param string $color
     * @param string $link
     * @param string[] $fields
     *
     * @return MessageAttachment
     */
    public function createAttachment($title, $message = '', $color = '', $link = '', $fields = [])
    {
        $attachment = new MessageAttachment();

        $attachment->setColor($color);

        $attachment->setTitle($title);

        $attachment->setTitleLink($link);

        $attachment->setFallback($title);

        foreach ($fields as $key => $value) {
            $attachment->addField($key, $value, true);
        }

        $attachment->setText($message);

        return $attachment;
    }

    /**
     * @param                         $message
     * @param array|MessageAttachment $attachments
     *
     * @param string $channel
     * @param string $user
     *
     * @return bool|Client\Response
     */
    public function sendMessage($message, $attachments = [], $channel = '#releases', $user = 'BDN')
    {
        if ($channel == null) {
            $channel = '#releases';
        }

        return $this->messenger->message(
            $channel,
            $message,
            $user,
            $attachments
        );
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function isInSlack($user)
    {
        $result = [
            'result' => false,
            'code' => 200,
        ];

        if ($this->getUsername($user) != null) {
            $result['result'] = true;
        }

        return $result;
    }

    public function getUsername(User $user)
    {
        $endpoint = $this->connection->getEndpoint();
        $url = 'https://'.$endpoint.'users.list?token=%s';
        $url = sprintf(
            $url,
            $this->connection->getToken()
        );

        $response = $this->executeRequest($url);

        if ($response->getStatusCode() != 200) {
            return null;
        }

        $responseArray = json_decode($response->getBody(true), true);

        foreach ($responseArray['members'] as $member) {
            if (isset($member['profile']['email']) && ($email = $member['profile']['email']) != null) {
                if ($user->getEmail() == $email) {
                    return $member['name'];
                }
            }
        }

        return null;
    }

    private function executeRequest($url)
    {
        $guzzle = new \Guzzle\Http\Client();
        $guzzle->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) {
                if ($event['response']->getStatusCode() != 200) {
                    $event->stopPropagation();
                }
            }
        );

        return $guzzle->createRequest('GET', $url)->send();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function inviteToChannel($user)
    {
        $repository = $this->entityManager->getRepository('BDNUserBundle:Users\SlackInvite');

        $previousRegistrations = $repository->findByUser($user);
        if (count($previousRegistrations) > 3) {
            return ['result' => false, 'error' => 'Already tried to sign up 3 times', 'code' => 403];
        }

        if (!$this->connection->isValid()) {
            return [
                'result' => false,
                'error' => 'There\'s no connection with Slack, please contact an administrator',
                'code' => 500,
            ];
        }

        $endpoint = $this->connection->getEndpoint();
        $url = 'https://'.$endpoint.'users.admin.invite?token=%s&email=%s&resend=%s';
        $url = sprintf(
            $url,
            $this->connection->getToken(),
            urlencode($user->getEmail()),
            count($previousRegistrations) <= 0 ? 'false' : 'true'
        );

        $response = $this->executeRequest($url);

        if ($response->getStatusCode() != 200) {
            return [
                'result' => false,
                'error' => 'Received an error status code from Slack, please contact an administrator',
                'code' => $response->getStatusCode(),
            ];
        }

        $responseArray = json_decode($response->getBody(true), true);

        $status = $responseArray['ok'];

        if ($status === false) {
            return [
                'result' => $responseArray['error'],
                'error' => 'Status is not true, please contact an administrator',
                'slack_error' => $responseArray,
                'code' => 500,
            ];
        }

        $slackInvite = new SlackInvite();
        $slackInvite->setDate(new \DateTime());
        $slackInvite->setUser($user);

        $this->entityManager->persist($slackInvite);
        $this->entityManager->flush();

        return ['result' => true, 'message' => 'You are invited to Slack, please check your email', 'code' => 200];
    }
}