<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Command;

use AppBundle\Entity\CronTask;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class CronTasksRunCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this->setName('crontasks:run')->setDescription('Runs Cron Tasks if needed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Running Cron Tasks...</comment>');

        $this->output = $output;
        /**
         * @var EntityManager $em
         * @var CronTask[] $crontasks
         */
        $em = $this->getContainer()->get('doctrine');
        $crontasks = $em->getRepository('AppBundle:CronTask')->findAll();

        foreach ($crontasks as $crontask) {
            $lastrun = $crontask->getLastrun() ? $crontask->getLastrun()->format('U') : 0;
            $nextrun = $lastrun + $crontask->getInterval();

            $run = (time() >= $nextrun);

            if ($run) {
                $output->writeln(sprintf('Running Cron Task <info>%s</info>', $crontask->getId()));

                // Set $lastrun for this crontask
                $crontask->setLastrun(new \DateTime());

                try {
                    $commands = $crontask->getCommands();
                    foreach ($commands as $command) {
                        $output->writeln(sprintf('Executing command <comment>%s</comment>...', $command));

                        // Run the command
                        $this->runCommand($command);
                    }

                    $output->writeln('<info>SUCCESS</info>');
                } catch (\Exception $e) {
                    $output->writeln('<error>ERROR: '.$e->getMessage().'</error>');
                }

                // Persist crontask
                $em->persist($crontask);
            } else {
                $output->writeln(sprintf('Skipping Cron Task <info>%s</info>', $crontask->getId()));
            }
        }

        // Flush database changes
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }

    private function runCommand($string)
    {
        // Split namespace and arguments
        $namespace = split(' ', $string)[1];

        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);

        // Send all output to the console
        $returnCode = $command->run($input, $this->output);

        return $returnCode != 0;
    }
}