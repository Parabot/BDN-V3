<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class TranslationHelper {

    const PATH = '/data/Translations/';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * TranslationHelper constructor.
     *
     * @param EntityManager   $entityManager
     * @param KernelInterface $kernel
     */
    public function __construct(EntityManager $entityManager, KernelInterface $kernel) {
        $this->entityManager = $entityManager;
        $this->kernel        = $kernel;
    }

    public function returnTranslation($key) {
        $content = file_get_contents($this->getPath() . $key . '.json');
        if($content != null && $content != false) {
            return json_decode($content, true);
        }

        return [];
    }

    private function getPath() {
        return $this->kernel->getRootDir() . self::PATH;
    }
}