<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Service;

use AppBundle\Entity\User;
use FOS\UserBundle\Util\UserManipulator;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Caller\ApiCallerInterface;
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;
use Symfony\Component\DependencyInjection\ContainerAware;

class Connector extends ContainerAware {

    private $url;
    private $key;

    public function setInformation() {
        $this->url = $this->container->getParameter('community.url');
        $this->key = $this->container->getParameter('community.key');
    }

    public function getCommunityUser($username, $password){
        if ($this->correctLogin($username, $password)){

            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);

            /**
             * @var $manipulator UserManipulator
             */
            $manipulator = $this->container->get('fos_user.util.user_manipulator');
            $manipulator->create($username, $password, 'my@mail.com', true, false);

            return $user;
        }
        return null;
    }

    public function correctLogin($username, $password){
        $saltObject = $this->performDo($this->createPerformArray('fetchSalt', 1, $username, null, md5($this->key . $username)));

        if (isset($saltObject->pass_salt)) {
            return $this->performDo(
                $this->createPerformArray(
                    'login',
                    1,
                    $username,
                    $this->getPasswordHashed($password, $saltObject->pass_salt),
                    md5($this->key.$username)
                )
            )->connect_status === 'SUCCESS';
        }
        return false;
    }

    /**
     * @param $fields
     *
     * @return \stdClass
     */
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
        if ($password != null){
            $array['password'] = $password;
        }
        if($key != null) {
            $array[ 'key' ] = $key;
        }

        return $array;
    }

    private function getPasswordHashed($password, $salt){
        return crypt($password, '$2a$13$' . $salt); // Simply the hashing method of IPB 4
    }
}