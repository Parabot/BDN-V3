<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Parabot\BDN\BotBundle\Entity\Types\Type;
use Travis\Client;

class TravisHelper {

    private $client;

    /**
     * TravisHelper constructor.
     */
    public function __construct() {
        $this->client = new Client();
    }

    /**
     * @param string $slug
     * @param int    $build_id
     *
     * @return null|Client\Entity\Build
     */
    public function getLatestBuild($slug, $build_id) {
        $repository = $this->client->fetchRepository($slug);
        $builds     = $repository->getBuilds();

        /** @var Client\Entity\Build $build */
        foreach($builds as $build) {
            if($build->getId() == $build_id) {
                return $build;
            }
        }

        return null;
    }

    /**
     * @param Type                $type
     * @param Client\Entity\Build $build
     *
     * @return string
     */
    public function getDownloadString($type, $build) {
        $rc = ($build->getBranch() == 'master' ?: '-RC-' . $build->getId());

        return $type->getName() . '-V' . $type->getVersion() . $rc;
    }
}