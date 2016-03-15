<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\CommunityBundle\Service;

use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Caller\ApiCallerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Connector extends ContainerAware {


    private $url;
    private $key;

    public function setInformation() {
        $this->url = $this->container->getParameter('community.url');
        $this->key = $this->container->getParameter('community.key');
    }

    public function performLogin($username, $password){
        return $this->performDo(
            $this->createPerformArray('login', 1, $username, $password, md5($this->key . $username))
        );
    }

    private function performDo($fields){
        /**
         * @var $caller ApiCallerInterface
         */
        $caller = $this->container->get('api_caller');

        // Fields as second array, so it has priority over the default
        $merged = array_merge(array('key' => $this->key), $fields);

        return $caller->call(
            new HttpGetJson(
                $this->url, $merged
            )
        );
    }

    private function createPerformArray($action = null, $type = null, $id = null, $password = null, $key = null) {
        $array = [ ];

        if($action != null) {
            $array[ 'do' ] = $action;
        }
        if($type != null) {
            $array[ 'idType' ] = $type;
        }
        if($id != null) {
            $array[ 'id' ] = $id;
        }
        if($password != null) {
            $array[ 'password' ] = $this->getPasswordHashed($password);
        }
        if($key != null) {
            $array[ 'key' ] = $key;
        }

        return $array;
    }

    private function getPasswordHashed($password){
        return md5($password);
    }

//    public function isCorrectUser($username, $password)
}