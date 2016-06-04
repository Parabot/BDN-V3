<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use DZunke\SlackBundle\Slack\Client;
use DZunke\SlackBundle\Slack\Client\Actions;
use DZunke\SlackBundle\Slack\Entity\MessageAttachment;
use DZunke\SlackBundle\Slack\Messaging;
use DZunke\SlackBundle\Slack\Messaging\IdentityBag;

class SlackManager {

    private $messenger;

    /**
     * @var IdentityBag
     */
    private $identityBag;

    /**
     * SlackManager constructor.
     *
     * @param Messaging   $messenger
     * @param IdentityBag $identityBag
     */
    public function __construct(Messaging $messenger, IdentityBag $identityBag) {
        $this->messenger   = $messenger;
        $this->identityBag = $identityBag;
    }

    /**
     * @param string   $title
     * @param string   $message
     * @param string   $link
     * @param string[] $fields
     * @param string   $channel
     */
    public function sendSuccessMessage($title, $message, $link = '', $fields = [ ], $channel = '') {
        $attachment = $this->createAttachment($title, $message, 'good', $link, $fields);

        $this->sendMessage('', [ $attachment ], $channel);
    }

    /**
     * @param          $title
     * @param string   $message
     * @param string   $color
     * @param string   $link
     * @param string[] $fields
     *
     * @return MessageAttachment
     */
    public function createAttachment($title, $message = '', $color = '', $link = '', $fields = [ ]) {
        $attachment = new MessageAttachment();

        $attachment->setColor($color);

        $attachment->setTitle($title);

        $attachment->setTitleLink($link);

        $attachment->setFallback($title);

        foreach($fields as $key => $value) {
            $attachment->addField($key, $value, true);
        }

        $attachment->setText($message);

        return $attachment;
    }

    /**
     * @param                         $message
     * @param array|MessageAttachment $attachments
     *
     * @param string                  $channel
     * @param string                  $user
     *
     */
    public function sendMessage($message, $attachments = [ ], $channel = '#releases', $user = 'BDN') {
        if ($channel == null){
            $channel = '#releases';
        }
        $this->messenger->message(
            $channel,
            $message,
            $user,
            $attachments
        );
    }
}