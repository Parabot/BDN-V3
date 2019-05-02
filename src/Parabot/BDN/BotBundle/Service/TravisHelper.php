<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Parabot\BDN\BotBundle\Entity\Types\Type;
use Travis\Client;

class TravisHelper
{

    private $client;
    private $premiumClient;

    /**
     * TravisHelper constructor.
     * @param string $token
     */
    public function __construct($token)
    {
        $this->client = new Client();
        $this->premiumClient = new PremiumClient($token);
    }

    /**
     * @param string $slug
     *
     * @return Client\Entity\Repository
     */
    public function getRepository($slug)
    {
        return $this->client->fetchRepository('repos/'.$slug);
    }

    /**
     * @param string $slug
     * @param int $build_id
     *
     * @return null|Client\Entity\Build
     */
    public function getLatestBuild($slug, $build_id)
    {
        $repository = $this->client->fetchRepository('repos/'.$slug);
        $builds = $repository->getBuilds();

        /** @var Client\Entity\Build $build */
        foreach ($builds as $build) {
            if ($build->getId() == $build_id) {
                return $build;
            }
        }

        $repository = $this->premiumClient->fetchRepository('repos/'.$slug);
        $builds = $repository->getBuilds();

        /** @var Client\Entity\Build $build */
        foreach ($builds as $build) {
            if ($build->getId() == $build_id) {
                return $build;
            }
        }

        return null;
    }

    /**
     * @param Type $type
     * @param Client\Entity\Build $build
     *
     * @return string
     */
    public function getDownloadString($type, $build)
    {
        $rc = ($build->getBranch() == 'master' ?: '-RC-'.$build->getId());

        return $type->getName().'-V'.$type->getVersion().$rc;
    }
}
