<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityBuild;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityBuildType;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityEntity;

class TeamCityAPI {

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * TeamCityAPI constructor.
     *
     * @param string $endpoint
     * @param string $username
     * @param string $password
     */
    public function __construct($endpoint, $username, $password) {
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
    }

    public function getBuildTypes() {
        return $this->callPoint(TeamCityBuildType::class);
    }

    protected function callPoint($class, $method = 'GET', $parameters = []) {
        $reflectionClass = new \ReflectionClass($class);

        /**
         * @var TeamCityEntity $instance
         */
        $instance = $reflectionClass->newInstanceWithoutConstructor();
        $point    = $instance::getAPIPoint()->getValue();

        $client  = new Client([ 'base_uri' => $this->endpoint, 'auth' => [ $this->username, $this->password ] ]);
        $request = new Request($method, $point, [ 'Accept' => 'application/json' ], http_build_query($parameters));

        $promise       = $client->sendAsync($request)->then(
            function ($response) use ($instance) {
                /**
                 * @var Response $response
                 */
                if($response->getStatusCode() === 200) {
                    $result = json_decode($response->getBody()->getContents());

                    return $instance::parseResponse($result);
                }

                return false;
            }
        );
        $promiseResult = $promise->wait();

        if($promiseResult !== false) {
            return $promiseResult;
        } else {
            throw new \Exception('Error occurred while retrieving TeamCity API');
        }
    }

    public function getBuilds($projectId) {
        return $this->callPoint(
            TeamCityBuild::class,
            'GET',
            [ 'locator' => sprintf('affectedProject:(id:%s)', $projectId) ]
        );
    }

}