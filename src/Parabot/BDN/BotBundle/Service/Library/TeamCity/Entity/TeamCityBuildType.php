<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity;

use Parabot\BDN\BotBundle\Service\Library\TeamCity\TeamCityPoint;
use Symfony\Component\Serializer\Annotation\Groups;

class TeamCityBuildType implements TeamCityEntity {

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $name;

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $projectName;

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $projectId;

    /**
     * TeamCityBuildType constructor.
     */
    public function __construct() { }

    /**
     * @return TeamCityPoint
     */
    public static function getAPIPoint() {
        return TeamCityPoint::BUILD_TYPES();
    }

    /**
     * @param $result
     *
     * @return mixed
     */
    public static function parseResponse($result) {
        $buildTypes = [];

        foreach($result->buildType as $build) {
            $buildType = new TeamCityBuildType();
            $buildType->setId($build->id);
            $buildType->setName($build->name);
            $buildType->setProjectName($build->projectName);
            $buildType->setProjectId($build->projectId);

            $buildTypes[] = $buildType;
        }

        return $buildTypes;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getProjectName() {
        return $this->projectName;
    }

    /**
     * @param mixed $projectName
     */
    public function setProjectName($projectName) {
        $this->projectName = $projectName;
    }

    /**
     * @return mixed
     */
    public function getProjectId() {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId) {
        $this->projectId = $projectId;
    }
}