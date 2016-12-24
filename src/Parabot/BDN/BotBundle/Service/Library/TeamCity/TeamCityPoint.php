<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity;

use MyCLabs\Enum\Enum;

/**
 * Class TeamCityPoints
 * @method static TeamCityPoint BUILD_TYPES()
 * @method static TeamCityPoint BUILDS()
 * @method static TeamCityPoint PROJECTS()
 * @method static TeamCityPoint BUILD_QUEUE()
 * @method static TeamCityPoint BUILD_LOG()
 * @package Parabot\BDN\BotBundle\Service\Library\TeamCity
 */
class TeamCityPoint extends Enum {

    const BUILD_TYPES = 'buildTypes';
    const PROJECTS    = 'projects';
    const BUILDS      = 'builds';
    const BUILD_QUEUE = 'buildQueue';
    const BUILD_LOG   = '/httpAuth/downloadBuildLog.html';

}