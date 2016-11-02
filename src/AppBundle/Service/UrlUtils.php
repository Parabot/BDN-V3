<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;

class UrlUtils {

    /**
     * @var string
     */
    private $domain;

    /**
     * UrlUtils constructor.
     *
     * @param string $domain
     */
    public function __construct($domain) { $this->domain = $domain; }


    public function isValidHostWithTLD($url = null) {
        return $this->getHostWithTLD($url) == $this->domain;
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