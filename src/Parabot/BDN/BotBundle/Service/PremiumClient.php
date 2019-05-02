<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Buzz\Browser;
use Travis\Client;
use Travis\Client\Entity\BuildCollection;
use Travis\Client\Entity\Repository;

class PremiumClient extends Client
{
    /**
     * @var \Buzz\Browser
     */
    private $browser;

    private $token;

    public function __construct($token, Browser $browser = null)
    {
        parent::__construct($browser);

        if (null === $browser) {
            $browser = new Browser();
        }

        $this->setBrowser($browser);

        $this->token = $token;

        $this->apiUrl = 'https://api.travis-ci.com';
    }

    public function fetchRepository($slug)
    {
        $repositoryUrl = sprintf('%s/%s.json', $this->apiUrl, $slug);
        $buildsUrl = sprintf('%s/%s/builds.json', $this->apiUrl, $slug);

        $repository = new Repository();
        $repositoryArray = json_decode($this->browser->get($repositoryUrl, ['github_token' => $this->token])->getContent(), true);
        if (!$repositoryArray) {
            throw new \UnexpectedValueException(sprintf('Response is empty for url %s', $repositoryUrl));
        }
        $repository->fromArray($repositoryArray);

        $buildCollection = new BuildCollection(json_decode($this->browser->get($buildsUrl)->getContent(), true));
        $repository->setBuilds($buildCollection);

        return $repository;
    }

    /**
     * @param \Buzz\Browser
     *
     * @return self
     */
    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;
        return $this;
    }
}
