<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity\Slack;

class MessageAttachment extends \DZunke\SlackBundle\Slack\Entity\MessageAttachment {

    /**
     * @var string[]
     */
    protected $mrkdwn_in;

    /**
     * @return \string[]
     */
    public function getMrkdwnIn() {
        return $this->mrkdwn_in;
    }

    /**
     * @param \string[] $mrkdwn_in
     */
    public function setMrkdwnIn($mrkdwn_in) {
        $this->mrkdwn_in = $mrkdwn_in;
    }

    public function setDefaultMrkdwnIn() {
        $this->mrkdwn_in = [ 'text', 'pretext' ];
    }
}