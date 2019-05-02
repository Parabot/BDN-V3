<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Buzz\Browser;
use Travis\Client;

class PremiumClient extends Client
{
    public function __construct(Browser $browser = null)
    {
        parent::__construct($browser);
    }

    public function fetchRepository($slug)
    {
        $this->apiUrl = 'https://api.travis-ci.com';

        return parent::fetchRepository($slug);
    }
}
