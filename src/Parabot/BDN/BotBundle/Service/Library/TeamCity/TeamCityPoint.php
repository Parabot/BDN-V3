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
 * @package Parabot\BDN\BotBundle\Service\Library\TeamCity
 */
class TeamCityPoint extends Enum {

    const BUILD_TYPES = 'buildTypes';
    const PROJECTS    = 'projects';
    const BUILDS      = 'builds';

}