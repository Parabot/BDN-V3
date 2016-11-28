<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Command;

use Parabot\BDN\OAuthServerBundle\Service\ClientCreator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientCreateCommand extends ContainerAwareCommand {
    protected function configure() {
        $this->setName('bdn:oauth:client:create');
        $this->setDescription('Creates a new client');

        $this->addOption(
            'redirect-uri',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Sets the redirect uri. Use multiple times to set multiple uris.',
            null
        );

        $this->addOption(
            'grant-type',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Set allowed grant type. Use multiple times to set multiple grant types',
            null
        );

        $this->addOption(
            'name',
            null,
            InputOption::VALUE_REQUIRED,
            'Name of the application',
            null
        );

        $this->addOption('interactive', 'i', InputOption::VALUE_NONE, 'Prompt every missed payment parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $values = $this->fillWithInteractiveMode($input, $output);

        $creator = $this->getContainer()->get('oauth_client_creator');
        /**
         * @var JsonResponse $response
         */
        $response = $creator->createClient($values);
        $output->writeln('');
        $content = json_decode($response->getContent(), true);
        if($response->getStatusCode() !== 200) {
            $output->writeln($content[ 'result' ]);
        } else {
            $output->writeln('client_id: ' . $content[ 'client_id' ]);
            $output->writeln('secret_id: ' . $content[ 'secret_id' ]);
        }
        $output->writeln('');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string[]
     */
    private function fillWithInteractiveMode(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getHelperSet()->get('dialog');

        $output->writeLn('');

        $values = [];
        foreach(ClientCreator::ARGUMENTS as $key) {
            $values[ $key ] = null;
        }

        foreach($values as $key => $value) {
            if(($value = $input->getOption($key)) != null) {
                $values[ $key ] = $value;
            } else {
                $values[ $key ] = $this->promptField($key, $input, $output, $dialog);
            }
        }

        return $values;
    }

    /**
     * @param string                                            $field example: "custom-data"
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param mixed                                             $dialog
     *
     * @return string|null
     */
    private function promptField($field, InputInterface $input, OutputInterface $output, $dialog) {
        if( ! $input->getOption($field)) {
            return $dialog->ask($output, $this->promptFormat(ucfirst(str_replace('-', ' ', $field))));
        }

        return null;
    }

    /**
     * @param string $s
     *
     * @return string
     */
    private function promptFormat($s) {
        return str_pad($s . ': ', 20, ' ', STR_PAD_LEFT);
    }
}