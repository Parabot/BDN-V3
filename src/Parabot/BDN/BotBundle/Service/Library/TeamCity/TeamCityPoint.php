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
 * @method static TeamCityPoint VSC_ROOTS()
 * @method static TeamCityPoint VSC_ROOTS_URL()
 * @method static TeamCityPoint ARTIFACTS()
 * @package Parabot\BDN\BotBundle\Service\Library\TeamCity
 */
class TeamCityPoint extends Enum {

    const BUILD_TYPES   = 'buildTypes';
    const PROJECTS      = 'projects';
    const BUILDS        = 'builds';
    const ARTIFACTS     = 'builds/buildType:id:%s/artifacts/content/target_directory/%s';
    const BUILD_QUEUE   = 'buildQueue';
    const BUILD_LOG     = '/httpAuth/downloadBuildLog.html';
    const VSC_ROOTS     = 'vcs-roots';
    const VSC_ROOTS_URL = 'vcs-roots/id:%s/properties/url';

}