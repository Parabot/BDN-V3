<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Library\TeamCity\Entity;

use Parabot\BDN\BotBundle\Service\Library\TeamCity\TeamCityPoint;
use Symfony\Component\Serializer\Annotation\Groups;

class TeamCityBuild implements TeamCityEntity {

    /**
     * @Groups({"default"})
     *
     * @var int
     */
    private $id;

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $status;

    /**
     * @Groups({"default"})
     *
     * @var string
     */
    private $state;

    /**
     * TeamCityBuild constructor.
     */
    public function __construct() { }

    /**
     * @return TeamCityPoint
     */
    public static function getAPIPoint() {
        return TeamCityPoint::BUILDS();
    }

    /**
     * @param $result
     *
     * @return TeamCityBuild[]
     */
    public static function parseResponse($result) {
        $builds = [];

        foreach($result->build as $build) {
            $b = new TeamCityBuild();
            $b->setId($build->id);
            $b->setStatus($build->status);
            $b->setState($build->state);

            $builds[] = $b;
        }

        return $builds;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state) {
        $this->state = $state;
    }
}