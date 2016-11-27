<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Doctrine\ORM\EntityManager;
use Parabot\BDN\BotBundle\Entity\Servers\Hook;
use Parabot\BDN\BotBundle\Entity\Servers\Server;

class HooksManager {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * HooksManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createHookArray(Server $server, $version = null, $detailed = false) {
        $repository = $this->entityManager->getRepository('BDNBotBundle:Servers\Hook');
        $result     = $repository->findHooksByServer($server, $version);

        $hooks = [
            'interfaces' => [],
            'getters'    => [],
            'setters'    => [],
            'callbacks'  => [],
            'invokers'   => [],
        ];

        foreach($result as $item) {
            $value = [];
            if($detailed === true) {
                $value[ 'id' ] = $item->getId();
            }
            switch($item->getType()) {
                case Hook::INTERFACE_TYPE:
                    $hooks[ Hook::INTERFACE_TYPE ][] = array_merge($value, $item->toInterfaceArray());
                    break;
                case Hook::GETTER_TYPE:
                    $hooks[ Hook::GETTER_TYPE ][] = array_merge($value, $item->toGetterArray());
                    break;
                case Hook::SETTER_TYPE:
                    $hooks[ Hook::SETTER_TYPE ][] = array_merge($value, $item->toSetterArray());
                    break;
                case Hook::CALLBACK_TYPE:
                    $hooks[ Hook::CALLBACK_TYPE ][] = array_merge($value, $item->toCallbackArray());
                    break;
                case Hook::INVOKER_TYPE:
                    $hooks[ Hook::INVOKER_TYPE ][] = array_merge($value, $item->toInvokerArray());
                    break;
            }
        }

        return $hooks;
    }

    public function hookArrayToXML($hookArray) {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        $xml .= '<injector>';
        foreach($hookArray as $type => $types) {
            $xml .= '<' . $type . '>';
            foreach($types as $items) {
                $xml .= '<add>';
                foreach($items as $i => $k) {
                    $xml .= '<' . $i . '>';
                    $xml .= $k;
                    $xml .= '</' . $i . '>';
                }
                $xml .= '</add>';
            }
            $xml .= '</' . $type . '>';
        }
        $xml .= '</injector>';

        return $xml;
    }

    /**
     * @param $xml
     *
     * @return Hook[]
     */
    public function toHookType($xml) {
        $hooks = [];
        foreach($xml as $type => $adds) {
            foreach($adds as $add) {
                if(count($add) > 0 && is_array($add) && ( ! isset($add[ 0 ]) || ! is_array($add[ 0 ]))) {
                    $add = [ $add ];
                }

                foreach($add as $hook) {
                    $hookObject = new Hook();

                    switch($type) {
                        case Hook::INTERFACE_TYPE:
                            $hookObject->setType(Hook::INTERFACE_TYPE);
                            break;

                        case Hook::GETTER_TYPE:
                            $hookObject->setType(Hook::GETTER_TYPE);
                            break;

                        case Hook::SETTER_TYPE:
                            $hookObject->setType(Hook::SETTER_TYPE);
                            break;

                        case Hook::INVOKER_TYPE:
                            $hookObject->setType(Hook::INVOKER_TYPE);
                            break;

                        case Hook::CALLBACK_TYPE:
                            $hookObject->setType(Hook::CALLBACK_TYPE);
                            break;
                    }
                    foreach($hook as $key => $value) {
                        /* TODO: Make a switch out of this */
                        if($key == 'classname') {
                            $hookObject->setClassname($value);
                        } elseif($key == 'interface') {
                            $hookObject->setInterface($value);
                        } elseif($key == 'accessor') {
                            $hookObject->setAccessor($value);
                        } elseif($key == 'field') {
                            $hookObject->setField($value);
                        } elseif($key == 'methodname') {
                            $hookObject->setMethodname($value);
                        } elseif($key == 'desc') {
                            $hookObject->setDesctype($value);
                        } elseif($key == 'descfield') {
                            $hookObject->setDescfield($value);
                        } elseif($key == 'into') {
                            $hookObject->setIntoclass($value);
                        } elseif($key == 'callclass') {
                            $hookObject->setCallclass($value);
                        } elseif($key == 'callmethod') {
                            $hookObject->setCallmethod($value);
                        } elseif($key == 'calldesc') {
                            $hookObject->setCalldesc($value);
                        } elseif($key == 'callargs') {
                            $hookObject->setCallargs($value);
                        } elseif($key == 'invokemethod') {
                            $hookObject->setInvokemethod($value);
                        } elseif($key == 'argsdesc') {
                            $hookObject->setArgsdesc($value);
                        }
                    }
                    $hooks[] = $hookObject;
                }
            }
        }

        return $hooks;
    }
}