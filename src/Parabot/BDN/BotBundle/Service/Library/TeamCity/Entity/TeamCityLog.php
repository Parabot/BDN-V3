<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity;

use Parabot\BDN\BotBundle\Service\Library\TeamCity\TeamCityPoint;

class TeamCityLog implements TeamCityEntity {

    /**
     * @return TeamCityPoint
     */
    public static function getAPIPoint() {
        return TeamCityPoint::BUILD_LOG();
    }

    /**
     * @param $result
     *
     * @return mixed
     */
    public static function parseResponse($result) {
        return $result;
    }
}