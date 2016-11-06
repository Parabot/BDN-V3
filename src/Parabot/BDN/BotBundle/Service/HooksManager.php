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

    public function createHookArray(Server $server) {
        $repository = $this->entityManager->getRepository('BDNBotBundle:Servers\Hook');
        $result     = $repository->findHooksByServer($server);

        $hooks = [];

        foreach($result as $item) {
            switch($item->getType()) {
                case Hook::INTERFACE_TYPE:
                    $hooks[ Hook::INTERFACE_TYPE ][] = $item->toInterfaceArray();
                    break;
                case Hook::INVOKER_TYPE:
                    $hooks[ Hook::INVOKER_TYPE ][] = $item->toInvokerArray();
                    break;
                case Hook::GETTER_TYPE:
                    $hooks[ Hook::GETTER_TYPE ][] = $item->toGetterArray();
                    break;
                case Hook::SETTER_TYPE:
                    $hooks[ Hook::SETTER_TYPE ][] = $item->toSetterArray();
                    break;
                case Hook::CALLBACK_TYPE:
                    $hooks[ Hook::GETTER_TYPE ][] = $item->toCallbackArray();
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

}