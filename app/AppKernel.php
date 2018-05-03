<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            new Lsw\ApiCallerBundle\LswApiCallerBundle(),

            new Seferov\AwsBundle\SeferovAwsBundle(),

            new DZunke\SlackBundle\DZunkeSlackBundle(),

            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            new Http\HttplugBundle\HttplugBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),

            new Nelmio\CorsBundle\NelmioCorsBundle(),

            new MyBuilder\Bundle\CronosBundle\MyBuilderCronosBundle(),

            new Parabot\BDN\UserBundle\BDNUserBundle(),
            new Parabot\BDN\BotBundle\BDNBotBundle(),
            new Parabot\BDN\OAuthServerBundle\BDNOAuthServerBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(
            function (ContainerBuilder $container) {
                $container->setParameter('container.autowiring.strict_mode', true);
                $container->setParameter('container.dumper.inline_class_loader', true);

                $container->addObjectResource($this);
            }
        );
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getRootDir()
    {
        return __DIR__;
    }
}
