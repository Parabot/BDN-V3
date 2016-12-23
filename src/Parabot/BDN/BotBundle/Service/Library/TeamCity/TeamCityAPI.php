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
use SimpleXMLElement;

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

    /**
     * @param string|TeamCityPoint $class Either the class, implementing TeamCityEntity or a TeamCityPoint
     * @param string               $method
     * @param array                $parameters
     *
     * @param array                $headers
     *
     * @return mixed
     * @throws \Exception
     */
    protected function callPoint($class = null, $method = 'GET', $parameters = [], $headers = []) {
        if($class !== null && is_string($class)) {
            $reflectionClass = new \ReflectionClass($class);

            /**
             * @var TeamCityEntity $instance
             */
            $instance = $reflectionClass->newInstanceWithoutConstructor();
            $point    = $instance::getAPIPoint();
        } else {
            $instance = null;
            $point    = $class;
        }

        $point = $point->getValue();

        $client = new Client([ 'base_uri' => $this->endpoint, 'auth' => [ $this->username, $this->password ] ]);
        if(is_array($parameters)) {
            $parameters = http_build_query($parameters);
        }

        $defaultHeaders = [ 'Accept' => 'application/json' ];
        if(count($headers) > 0) {
            $headers = array_merge($defaultHeaders, $headers);
        }

        $request = new Request($method, $point, $headers, $parameters);

        $promise       = $client->sendAsync($request)->then(
            function ($response) use ($instance) {
                /**
                 * @var Response $response
                 */
                if($response->getStatusCode() === 200) {
                    $result = json_decode($response->getBody()->getContents());
                    if($instance !== null) {
                        return $instance::parseResponse($result);
                    } else {
                        return $result;
                    }
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

    public function startBuild($buildTypeId) {
        $build = new SimpleXMLElement('<build></build>');

        $buildType = $build->addChild('buildType', '');
        $buildType->addAttribute('id', $buildTypeId);

        $requestValue = $build->asXML();

        $result = $this->callPoint(
            TeamCityPoint::BUILD_QUEUE(),
            'POST',
            $requestValue,
            [ 'Content-Type' => 'application/xml' ]
        );

        return $result->state === 'queued';
    }

    public function getBuilds($projectId) {
        return $this->callPoint(
            TeamCityBuild::class,
            'GET',
            [ 'locator' => sprintf('affectedProject:(id:%s)', $projectId) ]
        );
    }

}