<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Command;

use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends ContainerAwareCommand {

    const VERBOSITY_LEVEL_MAP = [
        LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
    ];

    protected function configure() {
        $this->setName('sync:community')->setDescription('Syncs community users with our database')->addArgument(
            'timestamp',
            InputArgument::OPTIONAL,
            'Do you want a custom timestamp to be synced from'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $logger = new ConsoleLogger($output, self::VERBOSITY_LEVEL_MAP);

        $timestamp = ($timestamp = $input->getArgument('timestamp')) ? intval($timestamp) : null;
        $users     = $this->getContainer()->get('bdn_connector')->updateCommunityUsers($timestamp);

        if( ! empty($users[ 'new' ])) {
            foreach($users[ 'new' ] as $user) {
                $logger->info('Synced new user: ' . $user);
            }
        }
    }

}