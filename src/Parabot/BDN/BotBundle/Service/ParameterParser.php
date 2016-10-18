<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

class ParameterParser {

    /**
     * @param string $request
     *
     * @return bool
     */
    public static function parseStringToBoolean($request) {
        if( ! is_numeric($request)) {
            return $request == 'true' ? true : false;
        } else {
            return $request == 1 ? true : false;
        }
    }
}