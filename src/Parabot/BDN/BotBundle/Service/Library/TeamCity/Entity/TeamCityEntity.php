<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity;

use Parabot\BDN\BotBundle\Service\Library\TeamCity\TeamCityPoint;

interface TeamCityEntity {
    /**
     * @return TeamCityPoint
     */
    public static function getAPIPoint();

    /**
     * @param $result
     *
     * @return mixed
     */
    public static function parseResponse($result);
}