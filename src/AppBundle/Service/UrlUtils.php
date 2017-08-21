<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

class UrlUtils {

    /**
     * @var string[]
     */
    private $domains;

    /**
     * UrlUtils constructor.
     *
     * @param string $domains
     */
    public function __construct($domains) { $this->domains = $domains; }


    public function isValidHostWithTLD($url = null) {
        foreach($this->domains as $domain){
            if ($this->getHostWithTLD($url) == $domain){
                return true;
            }
        }
        return false;
    }

    public function getHostWithTLD($url = null) {
        if($url == null) {
            $url = $_SERVER[ 'REQUEST_URI' ];
        }

        $info = parse_url($url);
        $host = $info[ 'host' ];

        $host_names = explode('.', $host);

        return $host_names[ count($host_names) - 2 ] . '.' . $host_names[ count($host_names) - 1 ];
    }

}