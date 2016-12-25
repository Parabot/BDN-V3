<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityBuild;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityBuildType;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityEntity;
use Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity\TeamCityLog;
use SimpleXMLElement;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

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

        if($method === 'GET') {
            $point .= '?' . $parameters;
        }

        $defaultHeaders = [ 'Accept' => 'application/json', 'Cache-Control' => 'no-cache' ];
        $headers        = array_merge($defaultHeaders, $headers);
        $request        = new Request($method, $point, $headers, $parameters);

        try {
            $promise       = $client->sendAsync($request)->then(
                function ($response) use ($instance) {
                    /**
                     * @var Response $response
                     */
                    if($response->getStatusCode() === 200) {
                        $content = $response->getBody()->getContents();

                        if(($result = json_decode($content)) == null) {
                            $result = $content;
                        }

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
        } catch(AccountExpiredException $e) {
            var_dump($e->getMessage());
            throw new \Exception('Error occurred while retrieving TeamCity API');
        }

        if($promiseResult !== false) {
            return $promiseResult;
        } else {
            throw new \Exception('Error occurred while retrieving TeamCity API');
        }
    }

    public function startBuild($buildTypeId) {
        $build = new SimpleXMLElement('<build></build>');

        $buildType = $build->addChild('buildType');
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

    public function getBuild($buildId) {
        return $this->callPoint(
            TeamCityBuild::class,
            'GET',
            [ 'locator' => sprintf('id:%s', $buildId) ]
        );
    }

    public function getBuilds(Script $script) {
        return $this->callPoint(
            TeamCityBuild::class,
            'GET',
            [ 'locator' => sprintf('affectedProject:(id:%s)', $this->getProjectID($script)) ]
        );
    }

    /**
     * @param Script $script
     *
     * @return string
     */
    public function getProjectID(Script $script) {
        return 'SCRIPT' . '_' . $script->getId();
    }

    public function getBuildLog($buildId) {
        return $this->callPoint(
            TeamCityLog::class,
            'GET',
            [ 'buildId' => $buildId ]
        );
    }

    public function createProject(Script $script) {
        $project = new SimpleXMLElement('<newProjectDescription></newProjectDescription>');
        $project->addAttribute('name', $script->getName());
        $project->addAttribute('id', $this->getProjectID($script));
        $project->addChild('parentProject')->addAttribute('locator', 'id:_Root');

        $requestValue = $project->asXML();

        $result = $this->callPoint(
            TeamCityPoint::PROJECTS(),
            'POST',
            $requestValue,
            [ 'Content-Type' => 'application/xml' ]
        );

        return $result->name === $script->getName();
    }

    public function createBuildType(Script $script) {
        $build = new SimpleXMLElement('<buildType></buildType>');
        $build->addAttribute('id', $this->getProjectID($script));
        $build->addAttribute('name', $script->getId());
        $build->addAttribute('projectId', $this->getProjectID($script));

        $project = $build->addChild('project');
        $project->addAttribute('id', $this->getProjectID($script));
        $project->addAttribute('name', $script->getName());
        $project->addAttribute('parentProjectId', '_Root');

        $template = $build->addChild('template');
        $template->addAttribute('id', 'Script');
        $template->addAttribute('templateFlag', 'true');
        $template->addAttribute('projectName', '&lt;Root project&gt;');
        $template->addAttribute('projectId', '_Root');

        $vcs      = $build->addChild('vcs-root-entries');
        $vcsEntry = $vcs->addChild('vcs-root-entry');
        $vcsEntry->addAttribute('id', $this->getVCSID($script));
        $vcsRoot = $vcsEntry->addChild('vcs-root');
        $vcsRoot->addAttribute('id', $this->getVCSID($script));
        $vcsRoot->addAttribute('name', $script->getGit()->getUrl() . '#refs/heads/master');

        $triggers = $build->addChild('triggers');
        $trigger  = $triggers->addChild('trigger');
        $trigger->addAttribute('id', 'vcsTrigger');
        $trigger->addAttribute('type', 'vcsTrigger');
        $triggerProperties = $trigger->addChild('properties');

        $triggerPropertyValues = [ 'branchFilter' => '+:*', 'quietPeriodMode' => 'DO_NOT_USE' ];
        foreach($triggerPropertyValues as $name => $value) {
            $t = $triggerProperties->addChild('property');
            $t->addAttribute('name', $name);
            $t->addAttribute('value', $value);
        }

        $requestValue = $build->asXML();

        $result = $this->callPoint(
            TeamCityPoint::BUILD_TYPES(),
            'POST',
            $requestValue,
            [ 'Content-Type' => 'application/xml' ]
        );

        return $result->id;
    }

    public function getVCSID(Script $script) {
        return $this->getProjectID($script) . '_' . $script->getId();
    }

    /**
     * @param Script $script
     *
     * @return bool
     */
    public function createVSC(Script $script) {
        $build = new SimpleXMLElement('<vcs-root></vcs-root>');
        $build->addAttribute('name', $script->getId());
        $build->addAttribute('vcsName', 'jetbrains.git');
        $build->addAttribute('modificationCheckInterval', '5');

        $project = $build->addChild('project');
        $project->addAttribute('id', $this->getProjectID($script));
        $project->addAttribute('name', '&lt;Root project&gt;');

        $properties     = $build->addChild('properties');
        $propertyValues = [
            'username'         => 'git',
            'authMethod'       => 'PRIVATE_KEY_FILE',
            'branch'           => 'refs/heads/master',
            'privateKeyPath'   => '~/.ssh/id_rsa_shared',
            'ignoreKnownHosts' => true,
            'url'              => $script->getGit()->getUrl(),
            'usernameStyle'    => 'NAME',
        ];

        foreach($propertyValues as $name => $value) {
            $property = $properties->addChild('property');
            $property->addAttribute('name', $name);
            $property->addAttribute('value', $value);
        }

        $requestValue = $build->asXML();

        $result = $this->callPoint(
            TeamCityPoint::VSC_ROOTS(),
            'POST',
            $requestValue,
            [ 'Content-Type' => 'application/xml' ]
        );

        return $result->name === $script->getId();
    }

}