<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Command\Script;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;
use Parabot\BDN\BotBundle\Entity\Script;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ScriptsOutdatedCommand
 * @package AppBundle\Command\Script
 *
 *
 * @Cron(hour="/12", noLogs=true, server="web")
 */
class ScriptsOutdatedCommand extends ContainerAwareCommand {

    const VERBOSITY_LEVEL_MAP = [
        LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
    ];

    /**
     * @var ConsoleLogger
     */
    private $logger;

    protected function configure() {
        $this->setName('scripts:outdated')->setDescription('Syncs community users with our database');
        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'Run the entire operation and show report, but don’t save changes.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->logger = new ConsoleLogger($output, self::VERBOSITY_LEVEL_MAP);
        $this->logger->info('Starting to check for outdated scripts');

        $dry                = $input->getOption('dry-run') ? true : false;
        $doctrine           = $this->getContainer()->get('doctrine');
        $scriptRepository   = $doctrine->getRepository('BDNBotBundle:Script');
        $releasesRepository = $doctrine->getRepository('BDNBotBundle:Scripts\Release');
        $scripts            = $scriptRepository->findAll();
        $now                = new \DateTime();

        /**
         * @var Script[] $scripts
         */
        foreach($scripts as $script) {
            if($script->getActive() === true) {
                $release = $releasesRepository->getLatestRelease($script);
                if($release != null) {
                    if($release->getDate()->diff($now)->m >= 2) {
                        $this->setScriptActiveState($doctrine, $script);
                    }
                } else {
                    $this->setScriptActiveState($doctrine, $script);
                }
            }
        }

        $doctrine->getManager()->flush();
        $this->logger->info('Finished checking for outdated scripts');
    }

    /**
     * @param AbstractManagerRegistry $doctrine
     * @param Script                  $script
     * @param boolean                 $active
     */
    private function setScriptActiveState($doctrine, Script $script, $active = false) {
        $script->setActive($active);
        $doctrine->getManager()->persist($script);
        $this->logger->info('Set script ' . $script->getName() . ' ' . ($active === true ? 'active' : 'inactive') );
    }
}