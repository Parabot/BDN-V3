<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Parabot\BDN\BotBundle\Entity\Types\Type;
use Symfony\Component\HttpKernel\KernelInterface;

class TypeListener implements EventSubscriber {

    protected $kernel;

    /**
     * Constructor
     *
     * @param KernelInterface $kernel A kernel instance
     */
    public function __construct(KernelInterface $kernel) {
        $this->kernel = $kernel;
    }


    /**
     * On Post Load
     * This method will be trigerred once an entity gets loaded
     *
     * @param LifecycleEventArgs $args Doctrine event
     */
    public function postLoad(LifecycleEventArgs $args) {
        $this->setTypePath($args);
    }

    private function setTypePath(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if( ! ($entity instanceof Type)) {
            return;
        }

        $entity->setPath($this->kernel->getRootDir());
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $this->setTypePath($args);
    }

    public function postPersist(LifecycleEventArgs $args) {
        $this->setTypePath($args);
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents() {
        return [
            'postPersist',
            'postUpdate',
            'postLoad',
        ];
    }
}