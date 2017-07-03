<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class ServerSlackChannel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ServerSlackChannelRepository")
 */
class ServerSlackChannel {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Server
     *
     * @ORM\OneToOne(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\Server", mappedBy="slackChannel")
     * @Groups({"default"})
     */
    private $server;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255)
     * @Groups({"default"})
     */
    private $channel;

    /**
     * ServerSlackChannel constructor.
     *
     * @param Server $server
     * @param string $channel
     */
    public function __construct(Server $server, $channel) {
        $this->server  = $server;
        $this->channel = $channel;
    }

    /**
     * @return Server
     */
    public function getServer() {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getChannel() {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel) {
        $this->channel = $channel;
    }
}