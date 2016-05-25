<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

class TypeHelper {

    private static $types = [
        'client' => 'BDNBotBundle:Types\Client',
    ];

    /**
     * TypeHelper constructor.
     */
    public function __construct() { }

    public function typeExists($type){
        foreach(self::$types as $key => $value){
            if (strtolower($key) == strtolower($type)){
                return true;
            }
        }
        return false;
    }
    
    public function getRepositorySlug($type){
        return self::$types[strtolower($type)];
    }
}